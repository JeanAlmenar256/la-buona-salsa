<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearTablaUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
        'id'          => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
        'nombre'      => ['type' => 'VARCHAR', 'constraint' => '100'],
        'email'       => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
        'password'    => ['type' => 'VARCHAR', 'constraint' => '255'],
        'created_at'  => ['type' => 'DATETIME', 'null' => true],
        'direccion'   => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
        'telefono'    => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
        'entre_calle_1'  => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
        'entre_calle_2'  => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
        'cp'          => ['type' => 'VARCHAR', 'constraint' => '10', 'null' => true],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('usuarios');
    }

    public function down()
    {
        //
    }
}
