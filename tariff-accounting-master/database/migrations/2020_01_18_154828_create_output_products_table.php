<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutputProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('output_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('output_productable_id')->nullable();
            $table->string('output_productable_type')->nullable();
            $table->bigInteger('product_id');
            $table->bigInteger('warehouse_product_id');
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
        Schema::dropIfExists('output_products');
    }
}
