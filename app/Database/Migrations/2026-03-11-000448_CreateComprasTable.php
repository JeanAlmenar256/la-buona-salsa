<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComprasTable extends Migration
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
        'usuario_id' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'producto_id' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'cantidad' => [
            'type'       => 'INT',
            'constraint' => 11,
        ],
        'total' => [
            'type'       => 'DECIMAL',
            'constraint' => '10,2',
        ],
        'metodo_envio' => [
            'type'       => 'VARCHAR',
            'constraint' => '50',
            'null'       => true,
        ],
        'estado_pago' => [
            'type'       => 'VARCHAR',
            'constraint' => '20',
            'default'    => 'pendiente',
        ],
        'comprobante_url' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
            'null'       => true,
        ],
        'created_at' => [
            'type' => 'DATETIME',
            'null' => true,
        ],
    ]);
    $this->forge->addKey('id', true);
    // Relación con la tabla usuarios
    $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('compras');
}

    public function down()
    {
        //
    }
}
