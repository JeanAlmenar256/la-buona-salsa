<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ProductoModel;
use App\Libraries\EmailHelper;

class Home extends BaseController
{
    public function index()
    {
        $productos = [];
        try {
            $productoModel = new ProductoModel();
            $productos = $productoModel->findAll();
        } catch (\Throwable $e) {
            log_message('error', 'Error cargando productos de BD: ' . $e->getMessage());
        }

        // Fallback si la base de datos está vacía o no responde
        if (empty($productos)) {
            $productos = [
                [
                    'id'               => 1,
                    'nombre'           => 'Salsa Tradicional La Buona',
                    'descripcion'      => 'Elaborada artesanalmente con tomates perita seleccionados, aceite de oliva virgen extra, albahaca fresca y el toque secreto de la receta familiar.',
                    'precio'           => 6500.00,
                    'stock'            => 12,
                    'imagen_principal' => 'producto1.png',
                    'imagen_2'         => 'producto2.png',
                    'imagen_3'         => 'producto3.png',
                ],
                [
                    'id'               => 2,
                    'nombre'           => 'Salsa Picante Ahumada',
                    'descripcion'      => 'Para los amantes de las emociones fuertes. Con chiles asados a leña, notas ahumadas y la intensidad perfecta para acompañar carnes y tacos.',
                    'precio'           => 6900.00,
                    'stock'            => 8,
                    'imagen_principal' => 'producto2.png',
                    'imagen_2'         => 'producto1.png',
                    'imagen_3'         => 'producto3.png',
                ],
                [
                    'id'               => 3,
                    'nombre'           => 'Salsa Pesto Rustico & Nueces',
                    'descripcion'      => 'Fragante albahaca fresca machacada con nueces tostadas, queso parmesano curado y aceite de oliva de primera prensa.',
                    'precio'           => 7200.00,
                    'stock'            => 6,
                    'imagen_principal' => 'producto3.png',
                    'imagen_2'         => 'producto1.png',
                    'imagen_3'         => 'producto2.png',
                ]
            ];
        }

        $session = session();
        $data = [
            'isLoggedIn' => $session->get('isLoggedIn') ?? false,
            'usuario_id' => $session->get('usuario_id'),
            'nombre'     => $session->get('nombre'),
            'email'      => $session->get('email'),
            'avatar'     => $session->get('avatar') ?? 'default-user.png',
            'productos'  => $productos,
            'producto_destacado' => $productos[0] ?? null
        ];

        return view('index', $data);
    }

    public function registrarBasico()
    {
        $reglas = [
            'nombre'           => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|max_length[100]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]'
        ];

        $mensajes = [
            'nombre' => [
                'required'   => 'Por favor ingresa tu nombre completo.',
                'min_length' => 'El nombre debe tener al menos 3 caracteres.'
            ],
            'email' => [
                'required'    => 'El correo electrónico es obligatorio.',
                'valid_email' => 'Por favor ingresa un formato de correo válido.'
            ],
            'password' => [
                'required'   => 'La contraseña es obligatoria.',
                'min_length' => 'La contraseña debe tener un mínimo de 6 caracteres.'
            ],
            'password_confirm' => [
                'required' => 'Debes confirmar tu contraseña.',
                'matches'  => 'Las contraseñas no coinciden. Intenta de nuevo.'
            ]
        ];

        if (!$this->validate($reglas, $mensajes)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model    = new UsuarioModel();
        $nombre   = trim($this->request->getPost('nombre'));
        $email    = strtolower(trim($this->request->getPost('email')));
        $password = (string)$this->request->getPost('password');

        $usuarioExistente = $model->where('email', $email)->first();

        if ($usuarioExistente) {
            // Si ya está verificado, debe iniciar sesión
            if (!empty($usuarioExistente['email_verificado'])) {
                return redirect()->back()->withInput()->with('error', 'Este correo ya tiene una cuenta registrada y activa. Por favor, inicia sesión con tu contraseña.');
            }

            // Si está pendiente de verificación, actualizamos nombre y contraseña
            $model->update($usuarioExistente['id'], [
                'nombre'        => $nombre,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'password'      => $password
            ]);
            $usuarioId = $usuarioExistente['id'];
        } else {
            // Nuevo usuario con email_verificado = 0
            $usuarioId = $model->insert([
                'nombre'           => $nombre,
                'email'            => $email,
                'password_hash'    => password_hash($password, PASSWORD_DEFAULT),
                'password'         => $password,
                'rol'              => 'cliente',
                'email_verificado' => 0
            ]);
        }

        // Generar código numérico y token
        $datosToken = $model->generarTokenVerificacion($usuarioId);

        // Enviar email de activación
        $resEnvio = EmailHelper::enviarVerificacion($email, $nombre, $datosToken['codigo'], $datosToken['token']);

        // Guardar identificador pendiente en sesión para la pantalla de verificación
        session()->set([
            'pending_verify_id'    => $usuarioId,
            'pending_verify_email' => $email
        ]);

        // Código de prueba para desarrollo local en caso de que no haya servidor SMTP activo
        session()->setFlashdata('demo_codigo', $datosToken['codigo']);
        session()->setFlashdata('demo_token', $datosToken['token']);

        return redirect()->to(base_url('verificar-email'))->with('success', '¡Cuenta creada! Hemos enviado un código de 6 dígitos a ' . esc($email) . ' para verificar y activar tu cuenta.');
    }

    public function login()
    {
        $reglas = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('error', 'Por favor ingresa tu correo y contraseña.');
        }

        $email    = strtolower(trim($this->request->getPost('email')));
        $password = (string)$this->request->getPost('password');

        $model   = new UsuarioModel();
        $usuario = $model->autenticarCliente($email, $password);

        if (!$usuario) {
            return redirect()->back()->withInput()->with('error', 'Correo o contraseña incorrectos.');
        }

        // Si el usuario es administrador o superadmin
        if (in_array($usuario['rol'] ?? 'cliente', ['admin', 'superadmin'], true)) {
            session()->set([
                'usuario_id' => $usuario['id'],
                'nombre'     => $usuario['nombre'],
                'email'      => $usuario['email'],
                'rol'        => $usuario['rol'],
                'isLoggedIn' => true,
                'isAdmin'    => true,
                'avatar'     => $usuario['avatar'] ?? 'default-user.png'
            ]);
            return redirect()->to(base_url('admin/dashboard'))->with('msg_info', 'Sesión de Administrador iniciada.');
        }

        // Verificar si su email está validado
        if (empty($usuario['email_verificado'])) {
            $datosToken = $model->generarTokenVerificacion($usuario['id']);
            EmailHelper::enviarVerificacion($email, $usuario['nombre'], $datosToken['codigo'], $datosToken['token']);

            session()->set([
                'pending_verify_id'    => $usuario['id'],
                'pending_verify_email' => $email
            ]);
            session()->setFlashdata('demo_codigo', $datosToken['codigo']);
            session()->setFlashdata('demo_token', $datosToken['token']);

            return redirect()->to(base_url('verificar-email'))->with('error', 'Tu cuenta aún no está activa. Debes validar tu correo ingresando el código de seguridad.');
        }

        // Login exitoso
        session()->set([
            'usuario_id' => $usuario['id'],
            'nombre'     => $usuario['nombre'],
            'email'      => $usuario['email'],
            'rol'        => $usuario['rol'] ?? 'cliente',
            'isLoggedIn' => true,
            'avatar'     => $usuario['avatar'] ?? 'default-user.png'
        ]);

        if (empty($usuario['direccion']) || empty($usuario['telefono'])) {
            return redirect()->to(base_url('completar-datos/' . $usuario['id']));
        }

        return redirect()->to(base_url('/'))->with('msg_info', '¡Bienvenido/a de nuevo, ' . esc($usuario['nombre']) . '!');
    }

    public function mostrarVerificacion()
    {
        $session = session();
        $usuarioId = $session->get('pending_verify_id') ?? $session->get('usuario_id');

        if (!$usuarioId) {
            return redirect()->to(base_url('/'))->with('error', 'No hay ninguna cuenta pendiente de verificación.');
        }

        $model   = new UsuarioModel();
        $usuario = $model->find($usuarioId);

        if (!$usuario) {
            return redirect()->to(base_url('/'));
        }

        // Si ya está verificado
        if (!empty($usuario['email_verificado'])) {
            return redirect()->to(base_url('/'))->with('msg_info', 'Tu cuenta ya se encuentra verificada y activa.');
        }

        // Extraer código y token de la cuenta si está pendiente
        $codigo = null;
        $token  = null;
        if (!empty($usuario['token_verificacion'])) {
            $partes = explode(':', $usuario['token_verificacion']);
            $codigo = $partes[0] ?? null;
            $token  = $partes[1] ?? null;
        }

        return view('auth/verificar_email', [
            'email'       => $usuario['email'],
            'demo_codigo' => $codigo,
            'demo_token'  => $token
        ]);
    }

    public function procesarCodigoVerificacion()
    {
        $session   = session();
        $usuarioId = $session->get('pending_verify_id') ?? $session->get('usuario_id');
        $codigo    = trim($this->request->getPost('codigo'));

        if (!$usuarioId) {
            return redirect()->to(base_url('/'))->with('error', 'Sesión de verificación expirada. Por favor regístrate o inicia sesión.');
        }

        if (empty($codigo) || strlen($codigo) < 6) {
            return redirect()->back()->with('error', 'Por favor ingresa el código numérico de 6 dígitos.');
        }

        $model  = new UsuarioModel();
        $valido = $model->verificarPorCodigo($usuarioId, $codigo);

        if (!$valido) {
            return redirect()->back()->with('error', 'El código de activación ingresado es incorrecto. Por favor verifícalo.');
        }

        $usuario = $model->find($usuarioId);

        // Iniciar sesión definitiva
        session()->remove(['pending_verify_id', 'pending_verify_email']);
        session()->set([
            'usuario_id' => $usuario['id'],
            'nombre'     => $usuario['nombre'],
            'email'      => $usuario['email'],
            'rol'        => $usuario['rol'] ?? 'cliente',
            'isLoggedIn' => true,
            'avatar'     => $usuario['avatar'] ?? 'default-user.png'
        ]);

        if (empty($usuario['direccion']) || empty($usuario['telefono'])) {
            return redirect()->to(base_url('completar-datos/' . $usuario['id']))->with('success', '¡Excelente! Correo validado exitosamente. Ahora completa tus datos para el envío.');
        }

        return redirect()->to(base_url('carrito/checkout/1?usuario_id=' . $usuario['id']))->with('success', '¡Correo validado exitosamente! Ya puedes continuar con tu compra.');
    }

    public function verificarEmail($token = null)
    {
        if (empty($token)) {
            return redirect()->to(base_url('verificar-email'))->with('error', 'Enlace de verificación inválido.');
        }

        $model   = new UsuarioModel();
        $usuario = $model->verificarPorToken($token);

        if (!$usuario) {
            return redirect()->to(base_url('verificar-email'))->with('error', 'El enlace de activación es inválido o ya ha sido utilizado.');
        }

        session()->remove(['pending_verify_id', 'pending_verify_email']);
        session()->set([
            'usuario_id' => $usuario['id'],
            'nombre'     => $usuario['nombre'],
            'email'      => $usuario['email'],
            'rol'        => $usuario['rol'] ?? 'cliente',
            'isLoggedIn' => true,
            'avatar'     => $usuario['avatar'] ?? 'default-user.png'
        ]);

        if (empty($usuario['direccion']) || empty($usuario['telefono'])) {
            return redirect()->to(base_url('completar-datos/' . $usuario['id']))->with('success', '¡Correo verificado con éxito! Completa tus datos para coordinar el envío.');
        }

        return redirect()->to(base_url('carrito/checkout/1?usuario_id=' . $usuario['id']))->with('success', '¡Cuenta activada con éxito!');
    }

    public function reenviarVerificacion()
    {
        $session   = session();
        $usuarioId = $session->get('pending_verify_id') ?? $session->get('usuario_id');

        if (!$usuarioId) {
            return redirect()->to(base_url('/'))->with('error', 'No se encontró la cuenta a verificar.');
        }

        $model   = new UsuarioModel();
        $usuario = $model->find($usuarioId);

        if (!$usuario) {
            return redirect()->to(base_url('/'));
        }

        $datosToken = $model->generarTokenVerificacion($usuarioId);
        EmailHelper::enviarVerificacion($usuario['email'], $usuario['nombre'], $datosToken['codigo'], $datosToken['token']);

        session()->setFlashdata('demo_codigo', $datosToken['codigo']);
        session()->setFlashdata('demo_token', $datosToken['token']);

        return redirect()->to(base_url('verificar-email'))->with('success', 'Te hemos enviado un nuevo código de activación a tu correo.');
    }

    public function completarDatos($id)
    {
        $model = new UsuarioModel();
        $usuario = null;
        try {
            $usuario = $model->find($id);
        } catch (\Throwable $e) {}

        if ($usuario && empty($usuario['email_verificado'])) {
            session()->set(['pending_verify_id' => $usuario['id'], 'pending_verify_email' => $usuario['email']]);
            return redirect()->to(base_url('verificar-email'))->with('error', 'Por seguridad, debes validar tu correo electrónico antes de ingresar tus datos de envío.');
        }

        return view('completar_registro', [
            'usuario_id' => $id,
            'usuario'    => $usuario ?? ['id' => $id, 'nombre' => session()->get('nombre') ?? 'Cliente', 'email' => session()->get('email') ?? '']
        ]);
    }

    public function guardarDetalles()
    {
        $id = $this->request->getPost('id');

        $reglas = [
            'telefono'  => 'required|min_length[6]',
            'direccion' => 'required|min_length[4]',
            'cp'        => 'permit_empty',
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $model = new UsuarioModel();
            $model->update($id, [
                'direccion'     => $this->request->getPost('direccion'),
                'entre_calle_1' => $this->request->getPost('entre_calle_1'),
                'entre_calle_2' => $this->request->getPost('entre_calle_2'),
                'telefono'      => $this->request->getPost('telefono'),
                'cp'            => $this->request->getPost('cp'),
            ]);
            $usuario = $model->find($id);
        } catch (\Throwable $e) {
            $usuario = [
                'id'        => $id,
                'direccion' => $this->request->getPost('direccion'),
                'telefono'  => $this->request->getPost('telefono'),
                'cp'        => $this->request->getPost('cp'),
            ];
        }

        session()->set([
            'usuario_id' => $id,
            'nombre'     => $usuario['nombre'] ?? session()->get('nombre'),
            'email'      => $usuario['email'] ?? session()->get('email'),
            'isLoggedIn' => true
        ]);

        return redirect()->to(base_url('carrito/checkout/1?usuario_id=' . $id));
    }

    public function salir()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }

    public function pedido()
    {
        $producto = null;
        try {
            $db = \Config\Database::connect();
            $producto = $db->table('productos')->getWhere(['id' => 1])->getRowArray();
        } catch (\Throwable $e) {}
        
        if (!$producto) {
            $producto = [
                'id'               => 1,
                'nombre'           => 'Salsa Tradicional La Buona',
                'descripcion'      => 'Elaborada artesanalmente con los ingredientes más frescos.',
                'precio'           => 6500.00,
                'stock'            => 10,
                'imagen_principal' => 'producto1.png',
            ];
        }

        $data['producto']   = $producto;
        $data['isLoggedIn'] = session()->get('isLoggedIn') ?? false;
        $data['usuario_id'] = session()->get('usuario_id');

        return view('detalle_producto', $data);
    }
}