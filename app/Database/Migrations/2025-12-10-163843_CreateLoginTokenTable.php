<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoginTokenTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'user_id'     => ['type' => 'INT'],
            'token'       => ['type' => 'VARCHAR', 'constraint' => 255],

            'used'        => ['type' => 'TINYINT', 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('login_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('login_tokens');
    }
}