<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportesTable extends Migration
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

            'lugar_salida' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],

            'lugar_llegada' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],

            'cantidad_resultados' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
        ]);

       
        $this->forge->addKey('id', true);

       
        $this->forge->addKey('user_id');

       
        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

      
        $this->forge->createTable('reporte', true);
    }

    public function down()
    {
        $this->forge->dropTable('reporte');
    }
}

