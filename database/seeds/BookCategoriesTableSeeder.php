<?php

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\BookCategory;
use Illuminate\Database\Seeder;

class BookCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();

        $categories_list = [
            'Comic', 'Fantasy', 'History', 'Horror',
            'Literary Fiction', 'Romance', 'Sci-Fi',
            'Suspense', 'Biographies', 'Cookbooks',
            'Poetry', 'Self-Help', 'Philosophy'
        ];
        
        $categories = array_map(function($category) use ($faker) {
            return [
                'slug' => Str::of($category)->slug('-'),
                'title' => $category,
                'description' => $faker->realText(255, 2)
            ];
        }, $categories_list);

        BookCategory::insert($categories);

    }
}
