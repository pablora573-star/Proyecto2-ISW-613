<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRidesTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'origen' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'destino' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'fecha_viaje' => [
                'type' => 'DATE',
            ],
            'hora_viaje' => [
                'type' => 'TIME',
            ],
            'costo_espacio' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'cantidad_espacios' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'vehicle_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'fecha_creado' => [
                'type'    => 'DATETIME',
                'null'    => false,
                
            ],
        ]);
        $this->forge->addKey('id', true);
        //indices
        $this->forge->addKey('user_id');
        $this->forge->addKey('origen');
        $this->forge->addKey('destino');
        $this->forge->addKey('fecha_viaje');
        $this->forge->addKey('vehicle_id');

        //foraneas
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('vehicle_id', 'vehiculos', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('rides');
    }

    public function down()
    {
        $this->forge->dropTable('rides');
    }
}
