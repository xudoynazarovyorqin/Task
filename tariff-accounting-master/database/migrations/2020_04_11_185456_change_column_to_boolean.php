<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnToBoolean extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_ready_products', function (Blueprint $table) {
            $table->dropColumn('is_warehouse');
            $table->dropColumn('paid');
        });
        Schema::table('buy_ready_products', function (Blueprint $table) {
            $table->boolean('is_warehouse')->default(true);
            $table->boolean('paid')->default(false);
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
            $table->dropColumn('is_warehouse');
            $table->dropColumn('paid');
        });
        Schema::table('buy_ready_products', function (Blueprint $table) {
            $table->integer('is_warehouse')->default(1);
            $table->integer('paid')->default(0);
        });
    }
}
