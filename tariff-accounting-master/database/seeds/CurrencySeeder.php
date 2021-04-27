<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        if(\Illuminate\Support\Facades\Schema::hasTable('currencies')){
            \App\Currency::firstOrCreate([
                'rate' => 1,
                'code' => 'UZB',
                'active' => true,
                'reverse' => false,
                'reversed_rate' => 1,
                'symbol' => 'UZS',
                'name' => 'Узбекский сум',
            ]);
            \App\Currency::firstOrCreate([
                'rate' => 10000,
                'code' => 'USD',
                'active' => true,
                'reverse' => false,
                'reversed_rate' => 1,
                'symbol' => 'доллар',
                'name' => 'Доллар США',
            ]);
        }
    }
}
