<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObjectDatasToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('object_name')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->string('object_street')->nullable();
            $table->string('object_home')->nullable();
            $table->string('object_corps')->nullable();
            $table->string('object_flat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('object_name');
            $table->dropColumn('district_id');
            $table->dropColumn('object_street');
            $table->dropColumn('object_home');
            $table->dropColumn('object_corps');
            $table->dropColumn('object_flat');
        });
    }
}
