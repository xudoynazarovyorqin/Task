<?php

use Illuminate\Database\Seeder;

class LevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\Illuminate\Support\Facades\Schema::hasTable('levels')){
            \App\Level::create([
               'name' => 'ЧЕРНОВИК'
            ]);
            \App\Level::create([
               'name' => 'ОЧЕРЕДЬ'
            ]);
            \App\Level::create([
               'name' => 'ПРОИЗВОДСТВО'
            ]);
            \App\Level::create([
               'name' => 'УПАКОВКА'
            ]);
            \App\Level::create([
               'name' => 'КАРАНТИН'
            ]);
            \App\Level::create([
               'name' => 'ПЕРЕРАБОТКА (БРАК И ВОЗВРАТ)'
            ]);
            \App\Level::create([
               'name' => 'СКЛАД ГОТОВОЙ ПРОДУКЦИИ'
            ]);
        }
    }
}
