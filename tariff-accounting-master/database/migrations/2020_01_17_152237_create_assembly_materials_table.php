<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssemblyMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assembly_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('material_id');
            $table->bigInteger('assembly_id');
            $table->double('total',24,9)->default(0)->nullable();
            $table->double('ready',24,9)->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assembly_materials');
    }
}
