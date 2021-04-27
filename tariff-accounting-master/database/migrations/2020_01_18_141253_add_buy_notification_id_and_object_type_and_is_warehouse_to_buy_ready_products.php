<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuyNotificationIdAndObjectTypeAndIsWarehouseToBuyReadyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('buy_ready_products', 'order_id'))
        {
            Schema::table('buy_ready_products', function (Blueprint $table)
            {
                $table->renameColumn('order_id', 'object_id');
            });
        }

        Schema::table('buy_ready_products', function (Blueprint $table) {
            $table->string('object_type', 100)->nullable();
            $table->tinyInteger('is_warehouse')->default(0);
            $table->bigInteger('buy_notification_id')->nullable();
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
            $table->dropColumn('object_type');
            $table->dropColumn('is_warehouse');
            $table->dropColumn('buy_notification_id');
        });
    }
}
