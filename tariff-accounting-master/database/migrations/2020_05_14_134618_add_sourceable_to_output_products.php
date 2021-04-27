<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceableToOutputProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('output_products', function (Blueprint $table) {
            $table->nullableMorphs('sourceable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('output_products', function (Blueprint $table) {
            $table->dropMorphs('sourceable');
        });
    }
}
