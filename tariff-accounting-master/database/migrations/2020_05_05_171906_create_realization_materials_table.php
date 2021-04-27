<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRealizationMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realization_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('realization_id')->nullable();
            $table->bigInteger('material_id')->nullable();
            $table->decimal('quantity',24,9)->default(0.0);
            $table->decimal('issued_from_booked')->default(0.0);
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
        Schema::dropIfExists('realization_materials');
    }
}
