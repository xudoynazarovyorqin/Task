<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangeAttrToBuys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buys', function (Blueprint $table) {
            $table->renameColumn('order_id', 'object_id');
            $table->string('object_type', 100)->nullable();
            $table->tinyInteger('is_warehouse')->default(0);
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
        Schema::table('buys', function (Blueprint $table) {
            $table->renameColumn('object_id', 'order_id');
            $table->dropColumn('object_type');
            $table->dropColumn('is_warehouse');
            $table->dropColumn('deleted_at');
        });
    }
}
