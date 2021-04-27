<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSaleProductIdToWarehouseProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_products', function (Blueprint $table) {
            $table->bigInteger('sale_product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('warehouse_products')){
            if (Schema::hasColumn('warehouse_products','sale_product_id')){
                Schema::table('warehouse_products', function (Blueprint $table) {
                    $table->dropColumn('sale_product_id');
                });
            }
        }
    }
}
