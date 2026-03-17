<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Home extends BaseController
{
    public function index() { return view('index'); }

    public function registrarBasico()
    {
        $model = new UsuarioModel();
        $id = $model->insert([
            'nombre' => $this->request->getPost('nombre'),
            'email'  => $this->request->getPost('email'),
        ]);
        return redirect()->to(base_url('completar-datos/' . $id));
    }

    public function completarDatos($id)
    {
        return view('completar_registro', ['usuario_id' => $id]);
    }

    public function guardarDetalles()
    {
        $model = new UsuarioModel();
        $id = $this->request->getPost('id');

        $reglas = [
            'telefono' => 'required|numeric|min_length[8]',
            'direccion' => 'required|min_length[5]',
            'cp' => 'required|numeric',
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

        // Buscamos el producto para el checkout
        $db = \Config\Database::connect();
        $producto = $db->table('productos')->getWhere(['id' => 1])->getRowArray();

        if (!$producto) {
        return "Error: No se encontró el producto en la base de datos. Por favor, ejecuta /repararImagenes primero.";
    }
    
        return view('carrito/checkout', [
            'usuario_id' => $id,
            'producto'   => $producto
        ]);
    }

    public function pedido()
    {
        $db = \Config\Database::connect();
        $data['producto'] = $db->table('productos')->getWhere(['id' => 1])->getRowArray();
        return $data['producto'] ? view('detalle_producto', $data) : "No hay productos.";
    }

    public function repararImagenes()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('productos');
        $builder->truncate();
        $builder->insertBatch([
            [
                'nombre'           => 'Salsa Tradicional La Buona',
                'descripcion'      => 'Hecha con los materiales mas fresco!',
                'precio'           => 6500.00,
                'stock'            => 5,
                'imagen_principal' => 'producto1.png',
                'imagen_2'         => 'producto2.png',
                'imagen_3'         => 'producto3.png',
            ],
        ]);
        return "<h1>✅ Base de datos actualizada</h1><p><a href='".base_url('pedido')."'>Ver producto</a></p>";
    }
}