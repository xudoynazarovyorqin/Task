<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
			$table->string('surname')->nullable();
            $table->string('patronymic')->nullable();
            $table->integer('role_id')->unsigned()->nullable();
			$table->string('email')->nullable();
			$table->string('phone')->unique();
			$table->string('password');
			$table->boolean('verified')->default(User::UNVERIFIED);
			$table->boolean('locked')->default(User::UNLOCKED);
			$table->string('status')->default(User::STATUS_ACTIVE);
			$table->dateTime('last_login')->nullable();
			$table->text('access_token')->nullable();
			$table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
