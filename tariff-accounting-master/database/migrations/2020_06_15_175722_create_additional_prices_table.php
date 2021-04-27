<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('distribution_cost_id')->nullable();
            $table->nullableMorphs('additional_priceable');
            $table->double('price', 24, 9)->default(0);
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
        Schema::dropIfExists('additional_prices');
    }
}
