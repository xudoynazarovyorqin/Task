<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('total_price');
            $table->dropColumn('paid_price');
            $table->dropMorphs('agentable');
            $table->dropMorphs('contractable');
            $table->dropColumn('is_output');

            $table->nullableMorphs('sourceable');
            $table->double('amount',24)->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->double('total_price')->nullable();
            $table->double('paid_price')->nullable();
            $table->nullableMorphs('agentable');
            $table->nullableMorphs('contractable');
            $table->tinyInteger('is_output')->default(0)->nullable();

            $table->dropMorphs('sourceable');
            $table->dropColumn('amount');
        });
    }
}
