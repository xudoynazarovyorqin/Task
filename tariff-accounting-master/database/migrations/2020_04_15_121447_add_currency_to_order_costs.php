<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyToOrderCosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_costs', function (Blueprint $table) {
            $table->bigInteger('currency_id')->nullable();
            $table->double('rate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_costs', function (Blueprint $table) {
            $table->dropColumn('currency_id');
            $table->dropColumn('rate');
        });
    }
}
