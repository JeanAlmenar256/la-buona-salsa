<?php

namespace App\Controllers;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use App\Models\ProductoModel;

class Carrito extends BaseController
{
    public function checkout($idProducto = 1)
    {
        $productoModel = new ProductoModel();
        $producto = $productoModel->find($idProducto);

        // Si el producto no existe en la DB 
        if (!$producto) {
            return "El producto no existe. Por favor, ejecutá /repararImagenes";
        }

        // Recuperamos el ID del usuario de la sesión o de la URL
        $usuario_id = $this->request->getGet('usuario_id');

        return view('carrito/checkout', [
            'producto'   => $producto,
            'usuario_id' => $usuario_id,
            'total'      => $producto['precio']
        ]);
    }

    public function procesarPago()
    {
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

        $client = new PreferenceClient();
        
        $idProducto   = $this->request->getPost('id_producto');
        $usuario_id   = $this->request->getPost('usuario_id');
        $cantidadItem = (int)($this->request->getPost('cantidad') ?? 1);
        $costoEnvio   = (float)($this->request->getPost('metodo_envio') ?? 0);

        $productoModel = new ProductoModel();
        $producto = $productoModel->find($idProducto);

        try {
            $preference = $client->create([
                "items" => [
                    [
                        "title"       => $producto['nombre'],
                        "quantity"    => $cantidadItem,
                        "unit_price"  => (float)$producto['precio'],
                        "currency_id" => "ARS"
                    ],
                    [
                        "title"       => "Envío",
                        "quantity"    => 1,
                        "unit_price"  => $costoEnvio,
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
                'total'        => ($producto['precio'] * $cantidadItem) + $costoEnvio
            ]);

        } catch (MPApiException $e) {
            return "Error MP: " . json_encode($e->getApiResponse()->getContent());
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}