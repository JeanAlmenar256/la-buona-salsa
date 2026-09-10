<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ProductoModel;

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
        $model = new UsuarioModel();
        $nombre = trim($this->request->getPost('nombre'));
        $email  = trim($this->request->getPost('email'));

        if (empty($email) || empty($nombre)) {
            return redirect()->back()->with('error', 'Por favor ingresa tu nombre y correo.');
        }

        try {
            $usuario = $model->where('email', $email)->first();

            if (!$usuario) {
                $id = $model->insert([
                    'nombre' => $nombre,
                    'email'  => $email,
                ]);
                $usuario = $model->find($id);
            } else {
                $model->update($usuario['id'], ['nombre' => $nombre]);
            }
        } catch (\Throwable $e) {
            $usuario = [
                'id'        => 1,
                'nombre'    => $nombre,
                'email'     => $email,
                'direccion' => '',
                'telefono'  => ''
            ];
        }

        // Establecer sesión
        session()->set([
            'usuario_id' => $usuario['id'] ?? 1,
            'nombre'     => $usuario['nombre'],
            'email'      => $usuario['email'],
            'isLoggedIn' => true,
            'avatar'     => $usuario['avatar'] ?? 'default-user.png'
        ]);

        if (!empty($usuario['direccion']) && !empty($usuario['telefono'])) {
            return redirect()->to(base_url('carrito/checkout/1?usuario_id=' . ($usuario['id'] ?? 1)));
        }

        return redirect()->to(base_url('completar-datos/' . ($usuario['id'] ?? 1)));
    }

    public function completarDatos($id)
    {
        $usuario = null;
        try {
            $model = new UsuarioModel();
            $usuario = $model->find($id);
        } catch (\Throwable $e) {}

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