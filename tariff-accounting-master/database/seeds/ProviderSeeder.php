<?php

use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable(\App\Provider::TABLE_NAME)){
            \App\Provider::firstOrCreate([
                'name' => 'ООО "Поставщик"',
                'full_name' => 'Mister system admin',
            ]);
        }
    }
}
