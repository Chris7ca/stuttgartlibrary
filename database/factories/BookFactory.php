<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {

    $title = substr($faker->sentence(4, true), 0, -1);

    return [
        'slug' =>  Str::of($title)->slug('-'),
        'writer_id' => rand(1, 100),
        'book_category_id' => rand(1, 13),
        'title' => $title,
        'image' => $faker->imageUrl(720, 1080),
        'synopsis' => $faker->realText(1000, 2),
        'published_date' => $faker->dateTimeBetween('-80 years', 'now', null)
    ];
});
