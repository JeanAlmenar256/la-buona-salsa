<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearTablaProductos extends Migration
{
    public function up()
{
    $this->forge->addField([
        'id'          => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
        'nombre'      => ['type' => 'VARCHAR', 'constraint' => '100'],
        'descripcion' => ['type' => 'TEXT', 'null' => true],
        'precio'      => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        'stock'       => ['type' => 'INT', 'constraint' => 5],
        'imagen_principal' => ['type' => 'VARCHAR', 'constraint' => '255'],
        'imagen_2'    => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
        'imagen_3'    => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
        'imagen_4'    => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('productos');
}

    public function down()
    {
        //
    }
}
