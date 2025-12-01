<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVehiclesTable extends Migration
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
            'placa' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'marca' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'modelo' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'anio' => [
                'type'       => 'SMALLINT',
                'constraint' => 6,
            ],
            'capacidad_asientos' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 5,
            ],
            'foto_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        //indice
        $this->forge->addKey('user_id');
        //foranea
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('vehiculos');
    }

    public function down()
    {
        $this->forge->dropTable('vehiculos');
    }
}
