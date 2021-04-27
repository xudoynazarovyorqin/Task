<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayuzPaymentSystemParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('pay-uz.db_prefix') . 'payment_system_params', function (Blueprint $table) {
            $table->increments('id');
            $table->string('system');
            $table->string('label')->nullable();
            $table->string('name')->nullable();
            $table->text('value')->nullable();
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
        Schema::dropIfExists(config('pay-uz.db_prefix') . 'payment_system_params');
    }
}
