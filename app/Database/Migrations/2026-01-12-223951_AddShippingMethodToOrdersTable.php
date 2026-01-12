<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShippingMethodToOrdersTable extends Migration
{
    public function up()
    {
        $fields = [
            'shipping_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'payment_method',
                'comment'    => 'Shipping extension code',
            ],
        ];

        $this->forge->addColumn('orders', $fields);
        $this->forge->addKey('shipping_method');
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'shipping_method');
    }
}
