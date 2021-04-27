<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSourceableOutputMaterials extends Migration
{
    public function up()
    {
        Schema::table('output_materials', function (Blueprint $table) {
            $table->nullableMorphs('sourceable');
        });
    }

    public function down()
    {
        Schema::table('output_materials', function (Blueprint $table) {
            $table->dropMorphs('sourceable');
        });
    }
}
