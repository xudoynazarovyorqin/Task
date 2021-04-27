<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefectableToDefectProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('defect_products', function (Blueprint $table) {
            $table->nullableMorphs('defectable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('defect_products', function (Blueprint $table) {
            $table->dropColumn('defectable_id');
            $table->dropColumn('defectable_type');
        });
    }
}
