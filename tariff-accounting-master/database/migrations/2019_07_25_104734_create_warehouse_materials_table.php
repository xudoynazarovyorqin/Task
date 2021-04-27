<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('buy_id')->nullable();
            $table->integer('material_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->double('qty_weight')->nullable();
            $table->double('remainder')->nullable();
            $table->bigInteger('price')->nullable();
            $table->integer('workplace_id')->nullable();
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
        Schema::dropIfExists('warehouse_materials');
    }
}
