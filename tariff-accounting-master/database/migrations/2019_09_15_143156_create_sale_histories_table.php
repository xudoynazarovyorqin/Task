<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id')->nullable();
            $table->text('comment')->nullable();
            $table->bigInteger('level_id')->nullable();
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('sale_histories');
    }
}
