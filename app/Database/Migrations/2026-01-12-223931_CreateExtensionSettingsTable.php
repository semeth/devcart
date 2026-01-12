<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExtensionSettingsTable extends Migration
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
            'extension_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Foreign key to extensions table',
            ],
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'comment'    => 'Setting name/key',
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Setting value (can be encrypted)',
            ],
            'is_encrypted' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => 'Whether value is encrypted',
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
        $this->forge->addKey('extension_id');
        $this->forge->addKey('setting_key');
        $this->forge->addUniqueKey(['extension_id', 'setting_key']);
        $this->forge->addForeignKey('extension_id', 'extensions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('extension_settings');
    }

    public function down()
    {
        $this->forge->dropTable('extension_settings');
    }
}
