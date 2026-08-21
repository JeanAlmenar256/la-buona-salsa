<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ProductoModel;

class Home extends BaseController
{
    public function index()
    {
        $productoModel = new ProductoModel();
        $productos = $productoModel->findAll();

        // Fallback si la base de datos está vacía
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

        $usuario = $model->where('email', $email)->first();

        if (!$usuario) {
            $id = $model->insert([
                'nombre' => $nombre,
                'email'  => $email,
            ]);
            $usuario = $model->find($id);
        } else {
            // Actualizar nombre si cambió
            $model->update($usuario['id'], ['nombre' => $nombre]);
        }

        // Establecer sesión
        session()->set([
            'usuario_id' => $usuario['id'],
            'nombre'     => $usuario['nombre'],
            'email'      => $usuario['email'],
            'isLoggedIn' => true,
            'avatar'     => $usuario['avatar'] ?? 'default-user.png'
        ]);

        // Si ya tiene dirección y teléfono, pasa directo al checkout o al producto
        if (!empty($usuario['direccion']) && !empty($usuario['telefono'])) {
            return redirect()->to(base_url('carrito/checkout/1?usuario_id=' . $usuario['id']));
        }

        return redirect()->to(base_url('completar-datos/' . $usuario['id']));
    }

    public function completarDatos($id)
    {
        $model = new UsuarioModel();
        $usuario = $model->find($id);

        if (!$usuario) {
            return redirect()->to(base_url('/'));
        }

        return view('completar_registro', [
            'usuario_id' => $id,
            'usuario'    => $usuario
        ]);
    }

    public function guardarDetalles()
    {
        $model = new UsuarioModel();
        $id = $this->request->getPost('id');

        $reglas = [
            'telefono'  => 'required|min_length[6]',
            'direccion' => 'required|min_length[4]',
            'cp'        => 'permit_empty',
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'direccion'     => $this->request->getPost('direccion'),
            'entre_calle_1' => $this->request->getPost('entre_calle_1'),
            'entre_calle_2' => $this->request->getPost('entre_calle_2'),
            'telefono'      => $this->request->getPost('telefono'),
            'cp'            => $this->request->getPost('cp'),
        ]);

        $usuario = $model->find($id);

        // Asegurar que la sesión tenga los datos actualizados
        session()->set([
            'usuario_id' => $id,
            'nombre'     => $usuario['nombre'] ?? session()->get('nombre'),
            'email'      => $usuario['email'] ?? session()->get('email'),
            'isLoggedIn' => true
        ]);

        // Redirige al checkout del producto 1
        return redirect()->to(base_url('carrito/checkout/1?usuario_id=' . $id));
    }

    public function salir()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }

    public function pedido()
    {
        $db = \Config\Database::connect();
        $data['producto'] = $db->table('productos')->getWhere(['id' => 1])->getRowArray();
        
        if (!$data['producto']) {
            $data['producto'] = [
                'id'               => 1,
                'nombre'           => 'Salsa Tradicional La Buona',
                'descripcion'      => 'Elaborada artesanalmente con los ingredientes más frescos.',
                'precio'           => 6500.00,
                'stock'            => 10,
                'imagen_principal' => 'producto1.png',
            ];
        }

        $data['isLoggedIn'] = session()->get('isLoggedIn') ?? false;
        $data['usuario_id'] = session()->get('usuario_id');

        return view('detalle_producto', $data);
    }

    public function repararImagenes()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('productos');
        $builder->truncate();
        $builder->insertBatch([
            [
                'nombre'           => 'Salsa Tradicional La Buona',
                'descripcion'      => 'Hecha con los ingredientes más frescos y receta de autor familiar.',
                'precio'           => 6500.00,
                'stock'            => 15,
                'imagen_principal' => 'producto1.png',
                'imagen_2'         => 'producto2.png',
                'imagen_3'         => 'producto3.png',
            ],
            [
                'nombre'           => 'Salsa Picante Ahumada',
                'descripcion'      => 'Intensidad equilibrada con notas de humo y chiles seleccionados.',
                'precio'           => 6900.00,
                'stock'            => 8,
                'imagen_principal' => 'producto2.png',
                'imagen_2'         => 'producto1.png',
                'imagen_3'         => 'producto3.png',
            ],
            [
                'nombre'           => 'Salsa Pesto Rustico',
                'descripcion'      => 'Albahaca fresca, nueces tostadas y parmesano curado.',
                'precio'           => 7200.00,
                'stock'            => 6,
                'imagen_principal' => 'producto3.png',
                'imagen_2'         => 'producto1.png',
                'imagen_3'         => 'producto2.png',
            ]
        ]);
        return "<h1>✅ Base de datos actualizada con catálogo completo</h1><p><a href='".base_url('/')."'>Ir a la portada</a></p>";
    }
}