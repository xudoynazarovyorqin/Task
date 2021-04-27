<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssemblyItemProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assembly_item_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('assembly_item_id');
            $table->bigInteger('product_id');
            $table->double('quantity',24,9)->nullable()->default(0);
            $table->double('ready',24,9)->nullable()->default(0);
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
        Schema::dropIfExists('assembly_item_products');
    }
}
