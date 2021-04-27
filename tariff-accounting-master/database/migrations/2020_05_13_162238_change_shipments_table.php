<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeShipmentsTable extends Migration
{
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('invoice');
            $table->dropColumn('total_amount');
            $table->dropColumn('client_id');
            $table->dateTime('datetime')->nullable();
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('invoice')->nullable();
            $table->string('date')->nullable();
            $table->double('total_amount')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->dropColumn('datetime');
        });
    }
}
