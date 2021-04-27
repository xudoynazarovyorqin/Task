<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttrToBuyReadyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_ready_products', function (Blueprint $table) {
            $table->bigInteger('contract_provider_id')->nullable();
            $table->bigInteger('order_id')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('buy_ready_products'))
        {
            if (Schema::hasColumn('buy_ready_products','order_id'))
            {
                Schema::table('buy_ready_products', function (Blueprint $table) {
                    $table->dropColumn('order_id');
                });
            }
            if (Schema::hasColumn('buy_ready_products','contract_provider_id'))
            {
                Schema::table('buy_ready_products', function (Blueprint $table) {
                    $table->dropColumn('contract_provider_id');
                });
            }
            Schema::table('buy_ready_products', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
