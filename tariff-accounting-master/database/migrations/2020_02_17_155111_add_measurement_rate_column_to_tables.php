<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMeasurementRateColumnToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assembly_additional_materials', function (Blueprint $table) {
            $table->double('measurement_rate')->default(1);
        });
        Schema::table('assembly_item_materials', function (Blueprint $table) {
            $table->double('measurement_rate')->default(1);
        });
        Schema::table('sale_additional_materials', function (Blueprint $table) {
            $table->double('measurement_rate')->default(1);
        });
        Schema::table('sale_product_materials', function (Blueprint $table) {
            $table->double('measurement_rate')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assembly_additional_materials', function (Blueprint $table) {
            $table->dropColumn('measurement_rate');
        });
        Schema::table('assembly_item_materials', function (Blueprint $table) {
            $table->dropColumn('measurement_rate');
        });
        Schema::table('sale_additional_materials', function (Blueprint $table) {
            $table->dropColumn('measurement_rate');
        });
        Schema::table('sale_product_materials', function (Blueprint $table) {
            $table->dropColumn('measurement_rate');
        });

    }
}
