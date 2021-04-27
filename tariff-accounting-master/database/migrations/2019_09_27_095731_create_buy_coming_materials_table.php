<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyComingMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_coming_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('buy_id')->nullable();
            $table->bigInteger('material_id')->nullable();
            $table->bigInteger('warehouse_type_id')->nullable();
            $table->bigInteger('warehouse_material_id')->nullable();
            $table->double('qty_in_buy')->nullable();
            $table->double('qty_coming')->nullable();
            $table->bigInteger('price')->nullable();
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
        Schema::dropIfExists('buy_coming_materials');
    }
}
