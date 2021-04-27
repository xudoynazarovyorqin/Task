<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePricesWithDefaultValueInBuysBuyReadyProductsSaleReadyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('buys', 'paid_price'))
        {
            Schema::table('buys', function (Blueprint $table)
            {
                $table->dropColumn('paid_price');
            });
        }
        Schema::table('buys', function (Blueprint $table)
        {
            $table->double('paid_price',24,9)->default(0)->nullable();
        });

        if (Schema::hasColumn('buy_ready_products', 'paid_price'))
        {
            Schema::table('buy_ready_products', function (Blueprint $table)
            {
                $table->dropColumn('paid_price');
            });
        }
        Schema::table('buy_ready_products', function (Blueprint $table)
        {
            $table->double('paid_price',24,9)->default(0)->nullable();
        });

        if (Schema::hasColumn('sale_ready_products', 'payed_sum'))
        {
            Schema::table('sale_ready_products', function (Blueprint $table)
            {
                $table->dropColumn('payed_sum');
            });
        }
        Schema::table('sale_ready_products', function (Blueprint $table)
        {
            $table->double('payed_sum',24,9)->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buys_buy_ready_products_sale_ready_products', function (Blueprint $table) {
            //
        });
    }
}
