<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\Cost::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'sku' => $faker->numberBetween(10000,999999),
    ];
});
