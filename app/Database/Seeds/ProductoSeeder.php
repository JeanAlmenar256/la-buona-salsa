<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre'           => 'Salsa Tradicional La Buona Salsa',
                'descripcion'      => 'Hecha con los materiales mas fresco! Y ese sabor picante pero que encanta!',
                'precio'           => 6500.00,
                'stock'            => 5,
                'imagen_principal' => 'producto1.png',
                'imagen_2'         => 'producto2.png',
                'imagen_3'         => 'producto3.png',
            ],
            
        ];

        // Insertar los datos en la tabla 'productos'
        $this->db->table('productos')->insertBatch($data);
    }
}