<?php

use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\Illuminate\Support\Facades\Schema::hasTable('payment_types')){
            \App\PaymentType::firstOrCreate([
               'name' => 'Наличный',
               'is_deleted' => false,
               'key' => \App\PaymentType::CASH
            ]);
            \App\PaymentType::firstOrCreate([
               'name' => 'Перечисление',
               'is_deleted' => false,
               'key' => \App\PaymentType::TRANSFER
            ]);
            \App\PaymentType::firstOrCreate([
               'name' => 'Карта',
               'is_deleted' => false,
               'key' => \App\PaymentType::CARD
            ]);
            \App\PaymentType::firstOrCreate([
               'name' => 'С баланса',
               'is_deleted' => false,
               'key' => \App\PaymentType::FROM_BALANCE
            ]);
        }
    }
}
