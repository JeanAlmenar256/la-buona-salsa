<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuariosTable extends Migration
{
    public function up()
{
    $this->forge->addField([
        'id' => [
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'nombre' => [
            'type'       => 'VARCHAR',
            'constraint' => '150',
        ],
        'email' => [
            'type'       => 'VARCHAR',
            'constraint' => '150',
            'unique'     => true,
        ],
        'password' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
            'null'       => true,
        ],
        'avatar' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
            'default'    => 'default-user.png',
        ],
        'created_at' => [
            'type' => 'DATETIME',
            'null' => true,
        ],
        'updated_at' => [
            'type' => 'DATETIME',
            'null' => true,
        ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('usuarios');
}

    public function down()
    {
        //
    }
}
