<?php

use App\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('states')){
            State::firstOrCreate([
                'state'  => 'Ожидание',
                'status' => State::STATUS_WAITING
            ]);
            State::firstOrCreate([
                'state'  => 'Активный',
                'status' => State::STATUS_ACTIVE
            ]);
            State::firstOrCreate([
                'state'  => 'Приостановка',
                'status' => State::STATUS_SUSPENSE
            ]);
            State::firstOrCreate([
                'state'  => 'Закрыта',
                'status' => State::STATUS_CLOSED
            ]);
        }
    }
}
