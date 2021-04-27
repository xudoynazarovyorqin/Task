<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Material;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Material::class, function (Faker $faker) {
    return [
        'name' 			=> $faker->name,
        'type_id' 		=> $faker->numberBetween(1,3),
        'measurement_id'=> $faker->numberBetween(1,5),
        'sku'			=> $faker->numberBetween(10000,999999),
        'qty_weight' 	=> $faker->numberBetween(100,999),
        'price' 		=> $faker->numberBetween(1000,999999),
    ];
});
