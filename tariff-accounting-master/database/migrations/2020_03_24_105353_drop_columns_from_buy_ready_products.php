<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromBuyReadyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('buy_ready_products','warehouse_id')){
            Schema::table('buy_ready_products', function (Blueprint $table) {
                $table->dropColumn('warehouse_id');
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
        if (!Schema::hasColumn('buy_ready_products','warehouse_id')){
            Schema::table('buy_ready_products', function (Blueprint $table) {
                $table->bigInteger('warehouse_id')->nullable();
            });
        }
    }
}
