<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $data = [
    'nombre' => 'Admin',
    'email'  => 'admin@correo.com',
    'password' => password_hash('12345', PASSWORD_DEFAULT),
];
$this->db->table('usuarios')->insert($data);
    }
}
