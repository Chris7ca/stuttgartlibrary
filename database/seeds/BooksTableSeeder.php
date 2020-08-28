<?php

use App\Models\Book;
use App\Models\BookRecord;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        factory(Book::class, 500)->create();

        factory(BookRecord::class, 1500)->create();

    }
}
