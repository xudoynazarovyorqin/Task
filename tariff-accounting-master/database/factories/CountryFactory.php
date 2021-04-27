<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Country;
use Faker\Generator as Faker;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'full_name' => $faker->name,
        'code' => $faker->numberBetween(10000,999999),
    ];
});
