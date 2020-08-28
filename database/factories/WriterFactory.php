<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Writer;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Writer::class, function (Faker $faker) {
    
    $firstName  = $faker->firstName;
    $lastName   = $faker->lastName;
    $slug       = Str::of("$firstName $lastName")->slug('-');
    
    return [
        'slug' => $slug,
        'name' => $firstName,
        'lastname' => $lastName,
        'country' => $faker->country
    ];
});
