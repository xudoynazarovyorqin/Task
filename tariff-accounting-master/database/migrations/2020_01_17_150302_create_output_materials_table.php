<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutputMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('output_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('output_materialable_id')->nullable();
            $table->string('output_materialable_type')->nullable();
            $table->bigInteger('material_id');
            $table->bigInteger('warehouse_material_id');
            $table->double('quantity',24,9)->default(0)->nullable();
            $table->double('back',24,9)->default(0)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('output_materials');
    }
}
