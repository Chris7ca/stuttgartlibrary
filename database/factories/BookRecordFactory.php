<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BookRecord;
use Faker\Generator as Faker;

$factory->define(BookRecord::class, function (Faker $faker) {
    return [
        'book_id' => rand(1, 500),
        'isbn' => $faker->isbn13(),
        'price' => $faker->randomFloat(2, 5, 3000)
    ];
});
