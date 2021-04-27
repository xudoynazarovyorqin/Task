<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractProviderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_provider_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('provider_contract_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->double('qty')->nullable();
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
        Schema::dropIfExists('contract_provider_products');
    }
}
