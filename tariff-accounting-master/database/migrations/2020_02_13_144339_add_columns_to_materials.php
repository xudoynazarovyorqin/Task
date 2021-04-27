<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->boolean('measurement_changeable')->default(false);
            $table->bigInteger('additional_measurement_id')->nullable();
            $table->double('additional_measurement_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('measurement_changeable');
            $table->dropColumn('additional_measurement_id');
            $table->dropColumn('additional_measurement_rate');
        });
    }
}
