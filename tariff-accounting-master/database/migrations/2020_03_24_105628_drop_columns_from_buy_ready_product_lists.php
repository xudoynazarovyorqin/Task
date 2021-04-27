<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromBuyReadyProductLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('buy_ready_product_lists','total_price')){
            Schema::table('buy_ready_product_lists', function (Blueprint $table) {
                $table->dropColumn('total_price');
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
        if (!Schema::hasColumn('buy_ready_product_lists','total_price')){
            Schema::table('buy_ready_product_lists', function (Blueprint $table) {
                $table->double('total_price',24,9)->default(0)->nullable();
            });
        }
    }
}
