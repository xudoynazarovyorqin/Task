<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayuzPaymentSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('pay-uz.db_prefix') . 'payment_systems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('system')->unique();
            $table->string('status')->default(\Goodoneuz\PayUz\Models\PaymentSystem::NOT_ACTIVE);
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
        Schema::dropIfExists(config('pay-uz.db_prefix') . 'payment_systems');
    }
}
