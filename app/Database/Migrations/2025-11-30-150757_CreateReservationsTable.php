<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReservationsTable extends Migration
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

            'ride_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'pasajero_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'chofer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'cantidad_asientos' => [
                'type'       => 'TINYINT',
                'constraint' => 4,
                'default'    => 1,
            ],

            'estado' => [
                'type' => "ENUM('pendiente','aceptada','rechazada','cancelada','finalizada')",
                'default' => 'pendiente',
            ],

            'fecha_creado' => [
                'type'    => 'DATETIME',
                'null'    => false,
                
            ],

            'notified' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        //indices
        $this->forge->addKey('ride_id');
        $this->forge->addKey('pasajero_id');
        $this->forge->addKey('chofer_id');
        $this->forge->addKey('estado');
        //foraneas
        $this->forge->addForeignKey('ride_id', 'rides', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('pasajero_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('chofer_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('reservations');
    }

    public function down()
    {
        $this->forge->dropTable('reservations');
    }
}
