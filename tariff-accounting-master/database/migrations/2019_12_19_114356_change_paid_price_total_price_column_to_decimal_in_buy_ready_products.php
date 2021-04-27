<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePaidPriceTotalPriceColumnToDecimalInBuyReadyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_ready_products', function (Blueprint $table) {
            $table->decimal('paid_price',24,9)->change();
            $table->decimal('total_price',24,9)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_ready_products', function (Blueprint $table) {
            //
        });
    }
}
