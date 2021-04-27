<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => $faker->postcode,
        'weight' => $faker->numberBetween(999,99999),
        'minimum_price' => $faker->numberBetween(2000,6000000),
        'purchase_price' => $faker->numberBetween(2000,6000000),
        'selling_price' => $faker->numberBetween(2000,6000000),
        'minimum_balance' => $faker->numberBetween(10,600),
        'description' => $faker->text,
    ];
});
