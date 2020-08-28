<?php

use App\Models\Writer;
use Illuminate\Database\Seeder;

class WritersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        factory(Writer::class, 100)->create();

    }
}
