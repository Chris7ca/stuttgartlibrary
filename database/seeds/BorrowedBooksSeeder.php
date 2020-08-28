<?php

use Carbon\Carbon;
use Faker\Factory;
use App\Models\BookRecord;
use Illuminate\Database\Seeder;

class BorrowedBooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $records = BookRecord::select('id')
        ->inRandomOrder()
        ->limit(500)
        ->get();

        $records->each(function ($bookRecord, $key) use ($faker) {

            $date = $faker->dateTimeBetween('-3 years', 'now', null);
            $deadline = Carbon::create($date->format('Y-m-d'))->addMonths(rand(3, 6));
            $returnDate = Carbon::create($deadline->format('Y-m-d'))->subDays(rand(0, 60));
            $returnDate = $returnDate <= now() ? $returnDate : null;

            $bookRecord->borrowedBook()->attach(rand(11, 50), [
                'delivery_date' => $date,
                'deadline' => $deadline->format('Y-m-d'),
                'return_date' => $returnDate
            ]);
        });

    }
}
