<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class Usuario extends BaseController
{
    public function registrarBasico()
    {
        $model = new UsuarioModel();
        $email = $this->request->getPost('email');
        $nombre = $this->request->getPost('nombre');

        // Buscamos si el correo ya existe
        $usuario = $model->where('email', $email)->first();

        if (!$usuario) {
            // Si no existe, lo creamos de cero
            $model->insert([
                'nombre' => $nombre,
                'email'  => $email,
            ]);
            $usuario = $model->where('email', $email)->first();
        }

        // Guardamos los datos en la sesión para que el sistema lo "recuerde"
        session()->set([
            'usuario_id' => $usuario['id'],
            'nombre'     => $usuario['nombre'],
            'email'      => $usuario['email'],
            'isLoggedIn' => true
        ]);

        return redirect()->to(base_url('perfil'));
    }

    public function perfil()
{
    // Por ahora le pasamos un array vacío para que la tabla no de error
    $data['compras'] = []; 
    return view('perfil', $data);
}

    public function subirAvatar()
{
    $img = $this->request->getFile('avatar');

    // 1. Validar que sea una imagen válida
    if ($img->isValid() && !$img->hasMoved()) {
        
        // Generamos un nombre nuevo para la imagen (ej: 16234567.jpg)
        $nuevoNombre = $img->getRandomName();

        // 2. Movemos la imagen a la carpeta public/uploads/perfiles
        // Si la carpeta no existe, CodeIgniter la crea sola.
        $img->move(ROOTPATH . 'public/uploads/perfiles', $nuevoNombre);

        // 3. Actualizamos la Base de Datos
        $model = new \App\Models\UsuarioModel();
        $id = session()->get('usuario_id');
        
        $model->update($id, ['avatar' => $nuevoNombre]);

        // 4. Actualizamos la Sesión para que el cambio se vea al instante
        session()->set('avatar', $nuevoNombre);

        return redirect()->back()->with('msg', '¡Foto de perfil actualizada!');
    }

    return redirect()->back()->with('msg', 'Error al subir la imagen.');
}
    
}