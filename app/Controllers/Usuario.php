<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class Usuario extends BaseController
{
    public function registrarBasico()
    {
        $model = new UsuarioModel();
        $email = trim($this->request->getPost('email'));
        $nombre = trim($this->request->getPost('nombre'));

        if (empty($email) || empty($nombre)) {
            return redirect()->back()->with('error', 'Por favor ingresa tu nombre y correo.');
        }

        // Buscamos si el correo ya existe
        $usuario = $model->where('email', $email)->first();

        if (!$usuario) {
            $id = $model->insert([
                'nombre' => $nombre,
                'email'  => $email,
            ]);
            $usuario = $model->find($id);
        }

        // Guardamos los datos en la sesión
        session()->set([
            'usuario_id' => $usuario['id'],
            'nombre'     => $usuario['nombre'],
            'email'      => $usuario['email'],
            'isLoggedIn' => true,
            'avatar'     => $usuario['avatar'] ?? 'default-user.png'
        ]);

        if (empty($usuario['direccion']) || empty($usuario['telefono'])) {
            return redirect()->to(base_url('completar-datos/' . $usuario['id']));
        }

        return redirect()->to(base_url('perfil'));
    }

    public function perfil()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('/'))->with('msg_info', 'Inicia sesión para ver tu perfil.');
        }

        $model = new UsuarioModel();
        $usuario = $model->find(session()->get('usuario_id'));

        $data = [
            'usuario' => $usuario,
            'compras' => []
        ];

        return view('perfil', $data);
    }

    public function salir()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }

    public function subirAvatar()
    {
        $img = $this->request->getFile('avatar');

        if ($img->isValid() && !$img->hasMoved()) {
            $nuevoNombre = $img->getRandomName();
            $img->move(ROOTPATH . 'public/uploads/perfiles', $nuevoNombre);

            $model = new \App\Models\UsuarioModel();
            $id = session()->get('usuario_id');
            
            $model->update($id, ['avatar' => $nuevoNombre]);
            session()->set('avatar', $nuevoNombre);

            return redirect()->back()->with('msg', '¡Foto de perfil actualizada con éxito!');
        }

        return redirect()->back()->with('msg', 'Error al subir la imagen.');
    }
}