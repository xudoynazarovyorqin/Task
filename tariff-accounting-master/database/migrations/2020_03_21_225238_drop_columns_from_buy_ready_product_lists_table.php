<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromBuyReadyProductListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            if (Schema::hasColumn('buy_ready_product_lists','name')){
                Schema::table('buy_ready_product_lists', function (Blueprint $table) {
                    $table->dropColumn('name');
                });
            }
            if (Schema::hasColumn('buy_ready_product_lists','warehouse_type_id')){
                Schema::table('buy_ready_product_lists', function (Blueprint $table) {
                    $table->dropColumn('warehouse_type_id');
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
            if (!Schema::hasColumn('buy_ready_product_lists','name')){
                Schema::table('buy_ready_product_lists', function (Blueprint $table) {
                    $table->string('name')->nullable();
                });
            }
            if (!Schema::hasColumn('buy_ready_product_lists','warehouse_type_id')){
                Schema::table('buy_ready_product_lists', function (Blueprint $table) {
                    $table->bigInteger('warehouse_type_id')->nullable();
                });
            }
    }
}
