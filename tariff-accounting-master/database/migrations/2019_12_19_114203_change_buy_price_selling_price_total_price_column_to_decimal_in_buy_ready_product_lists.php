<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBuyPriceSellingPriceTotalPriceColumnToDecimalInBuyReadyProductLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_ready_product_lists', function (Blueprint $table) {
            $table->decimal('buy_price',24,9)->change();
            $table->decimal('selling_price',24,9)->change();
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
        Schema::table('buy_ready_product_lists', function (Blueprint $table) {
            //
        });
    }
}
