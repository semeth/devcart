<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExtensionsTable extends Migration
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
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'Extension type (payment, shipping, etc.)',
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'Unique extension code/identifier',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'comment'    => 'Display name',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Extension description',
            ],
            'version' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => '1.0.0',
                'comment'    => 'Extension version',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => 'Enable/disable flag',
            ],
            'is_default' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => 'Default method flag',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Display order',
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
        $this->forge->addKey('type');
        $this->forge->addKey('code');
        $this->forge->addKey('is_active');
        $this->forge->addUniqueKey(['type', 'code']);
        $this->forge->createTable('extensions');
    }

    public function down()
    {
        $this->forge->dropTable('extensions');
    }
}
