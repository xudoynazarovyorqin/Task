<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssemblyItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assembly_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('assembly_id');
            $table->bigInteger('product_id');
            $table->double('quantity',24,9);
            $table->double('ready',24,9);
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
        Schema::dropIfExists('assembly_items');
    }
}
