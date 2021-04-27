<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTransactionTable extends Migration
{
    public function up()
    {
        Schema::table('transactions',function (Blueprint $table){
            $table->nullableMorphs('contractable');
            $table->renameColumn('price','amount');
        });
    }

    public function down()
    {
        Schema::table('transactions',function (Blueprint $table){
            $table->dropMorphs('contractable');
            $table->renameColumn('amount','price');
        });
    }
}
