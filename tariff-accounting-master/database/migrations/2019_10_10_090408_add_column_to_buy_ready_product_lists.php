<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToBuyReadyProductLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_ready_product_lists', function (Blueprint $table) {
            $table->tinyInteger('is_full')->default(0);
            $table->double('not_enough')->nullable();
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
            $table->dropColumn('is_full');
            $table->dropColumn('not_enough');
        });
    }
}
