<?php

use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable(\App\Client::TABLE_NAME)){
            \App\Client::firstOrCreate([
               'name' => 'ООО "Покупатель"',
               'full_name' => 'Mister system admin',
            ]);
        }
    }
}
