<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyIdAndRateToBuyMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_materials', function (Blueprint $table) {
            $table->bigInteger('currency_id')->default(1)->nullable();
            $table->double('rate',24,9)->default(1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_materials', function (Blueprint $table) {
            $table->dropColumn('currency_id');
            $table->dropColumn('rate');
        });
    }
}
