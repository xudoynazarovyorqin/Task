<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeQuantityToBodyInBuyReadyProductNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('buy_ready_product_notifications', 'quantity'))
        {
            Schema::table('buy_ready_product_notifications', function (Blueprint $table)
            {
                $table->dropColumn('quantity');
            });
        }
        Schema::table('buy_ready_product_notifications', function (Blueprint $table) {
            $table->text('body')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_ready_product_notifications', function (Blueprint $table) {
            $table->dropColumn('body');
        });
    }
}
