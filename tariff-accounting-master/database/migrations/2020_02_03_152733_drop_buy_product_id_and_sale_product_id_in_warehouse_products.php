<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropBuyProductIdAndSaleProductIdInWarehouseProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('warehouse_products', 'buy_product_id'))
        {
            Schema::table('warehouse_products', function (Blueprint $table)
            {
                $table->dropColumn('buy_product_id');
            });
        }

        if (Schema::hasColumn('warehouse_products', 'sale_product_id'))
        {
            Schema::table('warehouse_products', function (Blueprint $table)
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
        Schema::table('warehouse_products', function (Blueprint $table) {
            //
        });
    }
}
