<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->nullable();
            $table->string('code')->nullable();
            $table->string('weight')->nullable();
            $table->string('nds')->nullable();
            $table->double('minimum_price')->nullable();
            $table->double('purchase_price')->nullable();
            $table->double('selling_price')->nullable();
            $table->string('minimum_balance')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('measurement_id')->nullable();
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
        Schema::dropIfExists('products');
    }
}
