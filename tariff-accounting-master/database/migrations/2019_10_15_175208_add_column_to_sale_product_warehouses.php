<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToSaleProductWarehouses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_product_warehouses', function (Blueprint $table) {
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
        if (Schema::hasTable('sale_product_warehouses') and Schema::hasColumn('sale_product_warehouses','sale_product_id'))
        {
            Schema::table('sale_product_warehouses', function (Blueprint $table) {
                $table->dropColumn('sale_product_id');
            });
        }
    }
}
