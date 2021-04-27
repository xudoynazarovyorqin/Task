<?php

use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('priorities')){
            \App\Priority::firstOrCreate([
                'name' => 'Высокая'
            ]);
            \App\Priority::firstOrCreate([
                'name' => 'Самые высокие'
            ]);
            \App\Priority::firstOrCreate([
                'name' => 'Низкая'
            ]);
            \App\Priority::firstOrCreate([
                'name' => 'Самые низкие'
            ]);
            \App\Priority::firstOrCreate([
                'name' => 'Нормальный'
            ]);
        }
    }
}
