<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('buy_id');
            $table->bigInteger('material_id');
            $table->string('name', 100)->nullable();
            $table->double('qty_weight')->nullable();
            $table->bigInteger('price')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_materials');
    }
}
