<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id');
            $table->bigInteger('material_id');
            $table->double('ready',17,2)->nullable()->default(0);
            $table->double('total',17,2)->nullable()->default(0);
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
        Schema::dropIfExists('sale_materials');
    }
}
