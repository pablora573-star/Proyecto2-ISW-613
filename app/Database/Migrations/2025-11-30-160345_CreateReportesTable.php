<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReporteTable extends Migration
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

            'fecha' => [
                'type' => 'DATE',
            ],
        ]);
        $this->forge->addKey('id', true);

        // Índice
        $this->forge->addKey('user_id');

        // foranea
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('reporte');
    }

    public function down()
    {
        $this->forge->dropTable('reporte');
    }
}
