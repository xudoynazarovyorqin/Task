<?php

use Illuminate\Database\Seeder;

class MeasurementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('measurements')){
            \App\Measurement::firstOrCreate([
                'name' => 'Месячный',
                'full_name' => "Ежемесячно",
                'code' => \App\Measurement::MONTHLY,
            ]);
            \App\Measurement::firstOrCreate([
                'name' => 'Почасовой',
                'full_name' => "Почасовой",
                'code' => \App\Measurement::HOURLY,
            ]);
        }
    }
}
