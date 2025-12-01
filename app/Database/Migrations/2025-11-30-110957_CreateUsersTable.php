<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
         $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre'       => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'apellido'      => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'cedula' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'fecha_nacimiento' => [
                'type' => 'DATE',
            ],
            'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'foto_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '225',
            ],
            'contra' => [
                'type'       => 'VARCHAR',
                'constraint' => '225',
            ],
            'rol' => [
                'type'       => "ENUM('administrador','chofer','pasajero')",
            ],
            'estado' => [
                'type'       => "ENUM('pendiente','activa','inactiva')",
                'default'    => 'pendiente',
            ],
            'activation_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'       => true,
            ],
            'token_expiry' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'fecha_creado' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        //indice
        $this->forge->addKey('activation_token');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
