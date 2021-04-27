<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeObjectAddressToDistrictIdToApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('applications', 'object_address'))
        {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('object_address');
            });
        }

        Schema::table('applications', function (Blueprint $table) {
            $table->bigInteger('district_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('applications', 'district_id'))
        {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('district_id');
            });
        }

        Schema::table('applications', function (Blueprint $table) {
            $table->string('object_address')->nullable();
        });
    }
}
