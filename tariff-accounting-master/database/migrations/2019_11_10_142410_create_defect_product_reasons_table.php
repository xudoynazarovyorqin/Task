<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefectProductReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defect_product_reasons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('defect_product_id')->nullable();
            $table->bigInteger('reason_id')->nullable();            
            $table->double('quantity',19,4)->default(0)->nullable();
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
        Schema::dropIfExists('defect_product_reasons');
    }
}
