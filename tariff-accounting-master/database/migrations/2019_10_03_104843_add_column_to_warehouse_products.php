<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToWarehouseProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_products', function (Blueprint $table) {
            $table->renameColumn('price', 'buy_price');
            $table->bigInteger('buy_product_id')->nullable();
            $table->double('total_coming')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('warehouse_products'))
        {
            if (Schema::hasColumn('warehouse_products','buy_price')){
                Schema::table('warehouse_products', function (Blueprint $table) {
                    $table->renameColumn('buy_price', 'price');
                });
            }
            if (Schema::hasColumn('warehouse_products','buy_product_id')){
                Schema::table('warehouse_products', function (Blueprint $table) {
                    $table->dropColumn('buy_product_id');
                });
            }
            if (Schema::hasColumn('warehouse_products','total_coming')){
                Schema::table('warehouse_products', function (Blueprint $table) {
                    $table->dropColumn('total_coming');
                });
            }
        }
    }
}
