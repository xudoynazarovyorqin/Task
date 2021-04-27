<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotEnoughMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('not_enough_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('buy_notification_id');
            $table->bigInteger('material_id');
            $table->double('quantity')->default(0)->nullable();
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
        Schema::dropIfExists('not_enough_materials');
    }
}
