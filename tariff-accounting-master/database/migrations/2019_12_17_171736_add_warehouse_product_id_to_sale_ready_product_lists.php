<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWarehouseProductIdToSaleReadyProductLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_ready_product_lists', function (Blueprint $table) {
            $table->bigInteger('warehouse_product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_ready_product_lists', function (Blueprint $table) {
            $table->dropColumn('warehouse_product_id');
        });
    }
}
