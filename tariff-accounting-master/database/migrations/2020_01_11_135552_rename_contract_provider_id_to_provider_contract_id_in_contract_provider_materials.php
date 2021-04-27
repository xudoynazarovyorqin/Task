<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameContractProviderIdToProviderContractIdInContractProviderMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        if (Schema::hasColumn('contract_provider_materials', 'contract_provider_id'))
        {
            Schema::table('contract_provider_materials', function (Blueprint $table)
            {
                $table->renameColumn('contract_provider_id', 'provider_contract_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_provider_materials', function (Blueprint $table) {
            //
        });
    }
}
