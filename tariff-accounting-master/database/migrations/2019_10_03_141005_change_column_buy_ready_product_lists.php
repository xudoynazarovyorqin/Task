<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnBuyReadyProductLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_ready_product_lists', function (Blueprint $table) {
            $table->renameColumn('price', 'buy_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_ready_product_lists', function (Blueprint $table) {
            $table->renameColumn('buy_price', 'price');
        });
    }
}
