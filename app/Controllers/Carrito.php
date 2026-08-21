<?php

namespace App\Controllers;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use App\Models\ProductoModel;
use App\Models\UsuarioModel;

class Carrito extends BaseController
{
    public function checkout($idProducto = 1)
    {
        $session = session();
        $usuario_id = $session->get('usuario_id') ?? $this->request->getGet('usuario_id');

        // Gated Checkout: Requerir registro previo
        if (!$usuario_id) {
            return redirect()->to(base_url('/'))->with('msg_info', 'Por favor, regístrate primero para realizar tu compra.');
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find($usuario_id);

        // Si el usuario no tiene dirección guardada, enviarlo a completar datos
        if ($usuario && (empty($usuario['direccion']) || empty($usuario['telefono']))) {
            return redirect()->to(base_url('completar-datos/' . $usuario_id));
        }

        $productoModel = new ProductoModel();
        $producto = $productoModel->find($idProducto);

        if (!$producto) {
            // Fallback si no está en la base de datos
            $producto = [
                'id'               => $idProducto,
                'nombre'           => 'Salsa Tradicional La Buona',
                'descripcion'      => 'Elaborada artesanalmente con los ingredientes más frescos.',
                'precio'           => 6500.00,
                'stock'            => 10,
                'imagen_principal' => 'producto1.png',
            ];
        }

        return view('carrito/checkout', [
            'producto'   => $producto,
            'usuario_id' => $usuario_id,
            'usuario'    => $usuario,
            'total'      => $producto['precio']
        ]);
    }

    public function procesarPago()
    {
        $idProducto   = $this->request->getPost('id_producto');
        $usuario_id   = $this->request->getPost('usuario_id');
        $cantidadItem = (int)($this->request->getPost('cantidad') ?? 1);
        $metodoEnvio  = $this->request->getPost('metodo_envio') ?? 'retiro';
        
        $costoEnvio = 0;
        if ($metodoEnvio === 'Rappi' || $metodoEnvio === 'Uber') {
            $costoEnvio = 1200;
        } elseif ($metodoEnvio === 'DiDi') {
            $costoEnvio = 950;
        }

        $productoModel = new ProductoModel();
        $producto = $productoModel->find($idProducto);

        if (!$producto) {
            $producto = [
                'id'               => 1,
                'nombre'           => 'Salsa Tradicional La Buona',
                'precio'           => 6500.00,
                'stock'            => 10,
                'imagen_principal' => 'producto1.png',
            ];
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find($usuario_id);

        $total = ($producto['precio'] * $cantidadItem) + $costoEnvio;

        // Intentar Mercado Pago si el token está configurado
        $mpToken = env('MP_ACCESS_TOKEN');
        if (!empty($mpToken) && $mpToken !== 'TU_ACCESS_TOKEN_AQUI') {
            try {
                MercadoPagoConfig::setAccessToken($mpToken);
                MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

                $client = new PreferenceClient();
                $preference = $client->create([
                    "items" => [
                        [
                            "title"       => $producto['nombre'],
                            "quantity"    => $cantidadItem,
                            "unit_price"  => (float)$producto['precio'],
                            "currency_id" => "ARS"
                        ],
                        [
                            "title"       => "Envío (" . $metodoEnvio . ")",
                            "quantity"    => 1,
                            "unit_price"  => (float)$costoEnvio,
                            "currency_id" => "ARS"
                        ]
                    ],
                    "back_urls" => [
                        "success" => base_url("pago/exitoso"),
                        "failure" => base_url("pago/fallido")
                    ],
                    "auto_return" => "approved",
                ]);

                return view('carrito/checkout', [
                    'preferenceId' => $preference->id,
                    'publicKey'    => env('MP_PUBLIC_KEY'),
                    'producto'     => $producto,
                    'usuario_id'   => $usuario_id,
                    'usuario'      => $usuario,
                    'cantidad'     => $cantidadItem,
                    'metodo_envio' => $metodoEnvio,
                    'costo_envio'  => $costoEnvio,
                    'total'        => $total
                ]);

            } catch (\Exception $e) {
                log_message('error', 'Error MP: ' . $e->getMessage());
            }
        }

        // Vista de confirmación directa (o simulación exitosa de pedido)
        return view('carrito/checkout', [
            'pedidoConfirmado' => true,
            'producto'     => $producto,
            'usuario_id'   => $usuario_id,
            'usuario'      => $usuario,
            'cantidad'     => $cantidadItem,
            'metodo_envio' => $metodoEnvio,
            'costo_envio'  => $costoEnvio,
            'total'        => $total
        ]);
    }

    public function exito()
    {
        return view('registro_exito', ['mensaje' => '¡Tu pedido de La Buona Salsa ha sido procesado con éxito!']);
    }

    public function fallo()
    {
        return redirect()->to(base_url('/'))->with('error', 'Hubo un inconveniente al procesar el pago. Por favor intenta nuevamente.');
    }
}