<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReservationOfToAssemblies extends Migration
{
    public function up()
    {
        Schema::table('assemblies', function (Blueprint $table) {
            $table->boolean('reservation_of')->default(true);
        });
    }

    public function down()
    {
        Schema::table('assemblies', function (Blueprint $table) {
            $table->dropColumn('reservation_of');
        });
    }
}
