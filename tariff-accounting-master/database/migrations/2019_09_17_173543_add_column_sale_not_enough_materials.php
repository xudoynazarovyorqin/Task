<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSaleNotEnoughMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_not_enough_materials', function (Blueprint $table) {
            $table->bigInteger('sale_not_enough_material_notification_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_not_enough_materials', function (Blueprint $table) {
            $table->dropColumn('sale_not_enough_material_notification_id');
        });
    }
}
