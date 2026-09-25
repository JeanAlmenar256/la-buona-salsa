<?php

namespace App\Libraries;

class EmailHelper
{
    /**
     * Enviar email de activación con código numérico de 6 dígitos y enlace directo
     */
    public static function enviarVerificacion(string , string , string , string ): array
    {
         = base_url('verificar-email/' . );

         = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #fdfaf6; margin: 0; padding: 0; color: #2c2523; }
                .email-container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #fae8d2; }
                .email-header { background: linear-gradient(135deg, #9B1212, #5a0a0a); color: #ffffff; padding: 35px 20px; text-align: center; }
                .email-header h1 { margin: 0; font-size: 26px; letter-spacing: 1px; }
                .email-header p { margin: 8px 0 0; font-size: 14px; color: #ffcca8; }
                .email-body { padding: 35px 30px; line-height: 1.6; }
                .code-box { font-size: 34px; letter-spacing: 8px; font-weight: bold; background: #fff4ea; border: 2px dashed #fd580f; color: #9B1212; padding: 18px; border-radius: 12px; text-align: center; margin: 25px 0; }
                .btn-activate { display: inline-block; background-color: #9B1212; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 50px; font-weight: bold; font-size: 16px; margin: 15px 0 25px; box-shadow: 0 4px 12px rgba(155, 18, 18, 0.25); }
                .email-footer { background-color: #f7f1eb; padding: 20px; text-align: center; font-size: 12px; color: #8c827a; }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <h1>🍅 LA BUONA SALSA</h1>
                    <p>Sabor Artesanal &bull; Calidad de Familia</p>
                </div>
                <div class='email-body'>
                    <h2 style='color: #9B1212; margin-top: 0;'>¡Bienvenido/a, " . htmlspecialchars() . "!</h2>
                    <p>Para proteger la seguridad de tu cuenta y confirmar que este correo es válido para tus pedidos y notificaciones de envío, por favor ingresa el siguiente código de activación de 6 dígitos:</p>
                    
                    <div class='code-box'>" . htmlspecialchars() . "</div>

                    <p style='text-align: center;'>O si lo prefieres, puedes activar tu cuenta haciendo clic directamente en el siguiente botón:</p>
                    
                    <div style='text-align: center;'>
                        <a href='" .  . "' class='btn-activate'>Activar mi Cuenta Ahora &rarr;</a>
                    </div>

                    <p style='font-size: 13px; color: #777; margin-top: 25px;'>
                        Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                        <a href='" .  . "' style='color: #fd580f; word-break: break-all;'>" .  . "</a>
                    </p>
                </div>
                <div class='email-footer'>
                    &copy; " . date('Y') . " La Buona Salsa. Este correo es una notificación automática de seguridad. Si no creaste una cuenta con nosotros, puedes desestimarlo.
                </div>
            </div>
        </body>
        </html>";

         = \Config\Services::email();
        ->setTo();
        ->setFrom('no-reply@labuonasalsa.com.ar', 'La Buona Salsa');
        ->setSubject("🔐 Código de Activación:  - La Buona Salsa");
        ->setMessage();
        ->setMailType('html');

         = false;
         = null;
        try {
             = ->send(false);
            if (!) {
                 = ->printDebugger(['headers']);
            }
        } catch (\Throwable ) {
             = ->getMessage();
        }

        return [
            'success' => ,
            'error'   => ,
            'codigo'  => ,
            'token'   => ,
            'link'    => 
        ];
    }
}