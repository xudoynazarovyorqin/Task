<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropNumberContractFromBuysAndBuyReadyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('buys', 'number_contact'))
        {
            Schema::table('buys', function (Blueprint $table)
            {
                $table->dropColumn('number_contact');
            });
        }
        if (Schema::hasColumn('buy_ready_products', 'number_contact'))
        {
            Schema::table('buy_ready_products', function (Blueprint $table)
            {
                $table->dropColumn('number_contact');
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
        Schema::table('buys_and_buy_ready_products', function (Blueprint $table) {
            //
        });
    }
}
