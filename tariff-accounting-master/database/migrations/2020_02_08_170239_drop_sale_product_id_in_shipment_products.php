<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSaleProductIdInShipmentProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('shipment_products', 'sale_product_id'))
        {
            Schema::table('shipment_products', function (Blueprint $table)
            {
                $table->dropColumn('sale_product_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_products', function (Blueprint $table) {
            //
        });
    }
}
