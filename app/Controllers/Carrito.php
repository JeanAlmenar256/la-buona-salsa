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
        $usuario = null;
        try {
            $usuario = $usuarioModel->find($usuario_id);
        } catch (\Throwable $e) {}

        // Si el usuario no tiene dirección guardada, enviarlo a completar datos
        if ($usuario && (empty($usuario['direccion']) || empty($usuario['telefono']))) {
            return redirect()->to(base_url('completar-datos/' . $usuario_id));
        }

        $productoModel = new ProductoModel();
        $producto = null;
        try {
            $producto = $productoModel->find($idProducto);
        } catch (\Throwable $e) {}

        if (!$producto) {
            $producto = [
                'id'               => $idProducto,
                'nombre'           => 'Salsa Tradicional La Buona',
                'descripcion'      => 'Elaborada artesanalmente con los ingredientes más frescos.',
                'precio'           => 6500.00,
                'stock'            => 15,
                'imagen_principal' => 'producto1.png',
            ];
        }

        $costoEnvioDefault = 1200;
        return view('carrito/checkout', [
            'producto'     => $producto,
            'usuario_id'   => $usuario_id,
            'usuario'      => $usuario,
            'cantidad'     => 1,
            'metodo_envio' => 'Rappi',
            'costo_envio'  => $costoEnvioDefault,
            'total'        => $producto['precio'] + $costoEnvioDefault
        ]);
    }

    public function procesarPago()
    {
        $idProducto   = $this->request->getPost('id_producto') ?? 1;
        $usuario_id   = $this->request->getPost('usuario_id');
        $cantidadItem = (int)($this->request->getPost('cantidad') ?? 1);
        $metodoEnvio  = $this->request->getPost('metodo_envio') ?? 'Rappi';
        
        $costoEnvio = 0;
        if ($metodoEnvio === 'Rappi' || $metodoEnvio === 'Uber') {
            $costoEnvio = 1200;
        } elseif ($metodoEnvio === 'DiDi') {
            $costoEnvio = 950;
        }

        $productoModel = new ProductoModel();
        $producto = null;
        try {
            $producto = $productoModel->find($idProducto);
        } catch (\Throwable $e) {}

        if (!$producto) {
            $producto = [
                'id'               => 1,
                'nombre'           => 'Salsa Tradicional La Buona',
                'precio'           => 6500.00,
                'stock'            => 15,
                'imagen_principal' => 'producto1.png',
            ];
        }

        $usuarioModel = new UsuarioModel();
        $usuario = null;
        try {
            $usuario = $usuarioModel->find($usuario_id);
        } catch (\Throwable $e) {}

        if (!$usuario) {
            $usuario = [
                'id'            => $usuario_id ?? 1,
                'nombre'        => session()->get('nombre') ?? 'Cliente',
                'email'         => session()->get('email') ?? 'cliente@ejemplo.com',
                'direccion'     => session()->get('direccion') ?? 'Dirección registrada',
                'telefono'      => session()->get('telefono') ?? '-',
                'entre_calle_1' => '',
                'entre_calle_2' => '',
                'cp'            => session()->get('cp') ?? '-'
            ];
        }

        $total = ($producto['precio'] * $cantidadItem) + $costoEnvio;

        // Leer credenciales de Mercado Pago desde .env
        $mpToken     = env('MP_ACCESS_TOKEN') ?? getenv('MP_ACCESS_TOKEN') ?? $_ENV['MP_ACCESS_TOKEN'] ?? '';
        $mpPublicKey = env('MP_PUBLIC_KEY') ?? getenv('MP_PUBLIC_KEY') ?? $_ENV['MP_PUBLIC_KEY'] ?? '';

        $mpToken     = trim(str_replace(['"', "'"], '', (string)$mpToken));
        $mpPublicKey = trim(str_replace(['"', "'"], '', (string)$mpPublicKey));

        // Auto-detección: En Mercado Pago, el Access Token es el token largo (~70+ caracteres con IDs numéricos)
        // y la Public Key es el identificador corto en formato UUID (~44 caracteres).
        // Si el usuario los invirtió por error en el .env, los intercambiamos automáticamente:
        if (!empty($mpPublicKey) && !empty($mpToken)) {
            if (strlen($mpPublicKey) > strlen($mpToken) && preg_match('/-[0-9]{10,}-/i', $mpPublicKey)) {
                $temp = $mpToken;
                $mpToken = $mpPublicKey;
                $mpPublicKey = $temp;
            }
        }

        $preferenceId = null;
        $initPoint    = null;
        $mpError      = null;

        if (!empty($mpToken) && $mpToken !== 'TU_ACCESS_TOKEN_AQUI') {
            try {
                // Configurar Access Token en SDK de Mercado Pago
                MercadoPagoConfig::setAccessToken($mpToken);

                $client = new PreferenceClient();
                $items = [
                    [
                        "id"          => "prod-" . $idProducto,
                        "title"       => (string)$producto['nombre'],
                        "description" => "Salsa Artesanal La Buona",
                        "quantity"    => (int)$cantidadItem,
                        "unit_price"  => (float)$producto['precio'],
                        "currency_id" => "ARS"
                    ]
                ];

                if ($costoEnvio > 0) {
                    $items[] = [
                        "id"          => "envio",
                        "title"       => "Costo de Envío (" . $metodoEnvio . ")",
                        "description" => "Envío a domicilio",
                        "quantity"    => 1,
                        "unit_price"  => (float)$costoEnvio,
                        "currency_id" => "ARS"
                    ];
                }

                $preferenceData = [
                    "items" => $items,
                    "payer" => [
                        "name"  => (string)($usuario['nombre'] ?? 'Cliente'),
                        "email" => !empty($usuario['email']) ? (string)$usuario['email'] : 'comprador@labuonasalsa.com.ar'
                    ]
                ];

                // Mercado Pago rechaza IPs privadas (192.168.x.x, 127.0.0.1) en back_urls ("back_urls invalid. Wrong format").
                // Solo se configuran back_urls si es un dominio público o si se especifica MP_BACK_URL en .env
                $customBackUrl = env('MP_BACK_URL');
                $host = parse_url(base_url(), PHP_URL_HOST);
                $isIp = filter_var($host, FILTER_VALIDATE_IP);

                if (!empty($customBackUrl)) {
                    $preferenceData["back_urls"] = [
                        "success" => rtrim($customBackUrl, '/') . "/pago/exitoso",
                        "failure" => rtrim($customBackUrl, '/') . "/pago/fallido",
                        "pending" => rtrim($customBackUrl, '/') . "/pago/exitoso"
                    ];
                } elseif (!$isIp && !empty($host) && $host !== 'localhost') {
                    $preferenceData["back_urls"] = [
                        "success" => base_url("pago/exitoso"),
                        "failure" => base_url("pago/fallido"),
                        "pending" => base_url("pago/exitoso")
                    ];
                }

                try {
                    $preference = $client->create($preferenceData);
                } catch (MPApiException $e) {
                    // Si Mercado Pago rechaza las back_urls por formato, reintentar de inmediato sin back_urls
                    if (isset($preferenceData['back_urls'])) {
                        unset($preferenceData['back_urls']);
                        $preference = $client->create($preferenceData);
                    } else {
                        throw $e;
                    }
                }

                if ($preference && isset($preference->id)) {
                    $preferenceId = $preference->id;
                    $isSandbox    = str_starts_with($mpToken, 'TEST-');
                    $initPoint    = ($isSandbox && !empty($preference->sandbox_init_point))
                                    ? $preference->sandbox_init_point
                                    : ($preference->init_point ?? $preference->sandbox_init_point ?? null);
                }

            } catch (MPApiException $e) {
                $content = $e->getApiResponse() ? json_encode($e->getApiResponse()->getContent()) : $e->getMessage();
                log_message('error', 'Error MPApiException: ' . $content);
                $mpError = 'Error de Mercado Pago: ' . $content;
            } catch (\Throwable $e) {
                log_message('error', 'Error creando preferencia MP: ' . $e->getMessage());
                $mpError = 'Error conectando con Mercado Pago: ' . $e->getMessage();
            }
        } else {
            $mpError = 'Las credenciales de Mercado Pago (MP_ACCESS_TOKEN / MP_PUBLIC_KEY) no están configuradas en el archivo .env del servidor.';
        }

        // Si Mercado Pago generó la preferencia con éxito, mostramos el botón de pago
        if ($preferenceId && !empty($mpPublicKey)) {
            return view('carrito/checkout', [
                'preferenceId' => $preferenceId,
                'initPoint'    => $initPoint,
                'publicKey'    => $mpPublicKey,
                'producto'     => $producto,
                'usuario_id'   => $usuario['id'] ?? 1,
                'usuario'      => $usuario,
                'cantidad'     => $cantidadItem,
                'metodo_envio' => $metodoEnvio,
                'costo_envio'  => $costoEnvio,
                'total'        => $total
            ]);
        }

        // Si hubo un error con Mercado Pago, recargamos el checkout mostrando el mensaje explicativo
        return view('carrito/checkout', [
            'mpError'      => $mpError,
            'producto'     => $producto,
            'usuario_id'   => $usuario['id'] ?? 1,
            'usuario'      => $usuario,
            'cantidad'     => $cantidadItem,
            'metodo_envio' => $metodoEnvio,
            'costo_envio'  => $costoEnvio,
            'total'        => $total
        ]);
    }

    public function exito()
    {
        $idProducto   = $this->request->getGet('id_producto') ?? 1;
        $usuario_id   = $this->request->getGet('usuario_id');
        $cantidadItem = (int)($this->request->getGet('cant') ?? 1);
        $metodoEnvio  = $this->request->getGet('envio') ?? 'Rappi';

        $productoModel = new ProductoModel();
        $producto = null;
        try {
            $producto = $productoModel->find($idProducto);
        } catch (\Throwable $e) {}

        if (!$producto) {
            $producto = ['nombre' => 'Salsa Tradicional La Buona', 'precio' => 6500.00];
        }

        $usuarioModel = new UsuarioModel();
        $usuario = null;
        try {
            $usuario = $usuarioModel->find($usuario_id);
        } catch (\Throwable $e) {}

        $costoEnvio = 0;
        if ($metodoEnvio === 'Rappi' || $metodoEnvio === 'Uber') $costoEnvio = 1200;
        elseif ($metodoEnvio === 'DiDi') $costoEnvio = 950;

        $total = ($producto['precio'] * $cantidadItem) + $costoEnvio;

        // Disparar Email de Notificación
        $this->enviarNotificacionPedido($usuario, $producto, $cantidadItem, $metodoEnvio, $total, $costoEnvio);

        return view('registro_exito', [
            'mensaje'     => '¡Tu pago en Mercado Pago ha sido aprobado con éxito!',
            'usuario'     => $usuario,
            'producto'    => $producto,
            'cantidad'    => $cantidadItem,
            'metodo_envio'=> $metodoEnvio,
            'total'       => $total
        ]);
    }

    public function fallo()
    {
        return redirect()->to(base_url('/'))->with('error', 'El pago fue cancelado o rechazado. Puedes intentar nuevamente.');
    }

    /**
     * Enviar email de notificación automática con los datos del pedido
     */
    private function enviarNotificacionPedido($usuario, $producto, $cantidad, $metodoEnvio, $total, $costoEnvio)
    {
        $destinatario = env('ADMIN_EMAIL') ?? 'jean.caret.almenar256@gmail.com';
        $asunto = "🍅 ¡Nuevo Pedido Confirmado! - La Buona Salsa";

        $cuerpo = "
        <html>
        <head>
            <style>
                body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #fdfaf6; color: #333; }
                .card { background: #fff; padding: 25px; border-radius: 12px; border-top: 5px solid #9B1212; max-width: 600px; margin: 20px auto; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
                h2 { color: #9B1212; margin-top: 0; }
                .badge { background: #fd580f; color: #fff; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 0.85rem; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
                .total { font-size: 1.3rem; font-weight: bold; color: #9B1212; }
            </style>
        </head>
        <body>
            <div class='card'>
                <h2>🍅 ¡Nuevo Pedido Recibido en La Buona Salsa!</h2>
                <p>Se ha confirmado un nuevo pedido para preparar y despachar.</p>
                <hr>
                
                <h3>📦 Detalle del Producto</h3>
                <table>
                    <tr><th>Producto:</th><td><strong>{$producto['nombre']}</strong></td></tr>
                    <tr><th>Cantidad:</th><td><span class='badge'>{$cantidad} frasco(s)</span></td></tr>
                    <tr><th>Método de Envío:</th><td>{$metodoEnvio} ($ " . number_format($costoEnvio, 2, ',', '.') . ")</td></tr>
                    <tr><th>Total Abonado:</th><td class='total'>$ " . number_format($total, 2, ',', '.') . "</td></tr>
                    <tr><th>Estado:</th><td><strong style='color: #28a745;'>✅ Pago Aprobado en Mercado Pago</strong></td></tr>
                </table>

                <h3 style='margin-top: 25px;'>📍 Datos del Cliente para la Entrega</h3>
                <table>
                    <tr><th>Cliente:</th><td>" . esc($usuario['nombre'] ?? 'Cliente') . "</td></tr>
                    <tr><th>Email:</th><td>" . esc($usuario['email'] ?? '-') . "</td></tr>
                    <tr><th>Teléfono (WhatsApp):</th><td><strong>" . esc($usuario['telefono'] ?? '-') . "</strong></td></tr>
                    <tr><th>Dirección de Entrega:</th><td><strong>" . esc($usuario['direccion'] ?? '-') . "</strong></td></tr>
                    <tr><th>Entre Calles:</th><td>" . esc($usuario['entre_calle_1'] ?? '-') . " y " . esc($usuario['entre_calle_2'] ?? '-') . "</td></tr>
                    <tr><th>Código Postal:</th><td>" . esc($usuario['cp'] ?? '-') . "</td></tr>
                </table>
                <hr>
                <p style='font-size: 0.85rem; color: #777;'>Mensaje generado automáticamente por el sistema de La Buona Salsa.</p>
            </div>
        </body>
        </html>
        ";

        try {
            $email = \Config\Services::email();
            $email->setTo($destinatario);
            $email->setFrom('pedidos@labuonasalsa.com.ar', 'La Buona Salsa Pedidos');
            $email->setSubject($asunto);
            $email->setMessage($cuerpo);
            $email->setMailType('html');
            $email->send();
        } catch (\Throwable $e) {
            log_message('error', 'Error enviando email: ' . $e->getMessage());
        }
    }
}