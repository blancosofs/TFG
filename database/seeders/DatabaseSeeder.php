<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([

       FinalSeeder::class,      // --> S
        // DemoDataSeeder::class,    --> S
        // MockDataSeeder::class,    --> A
        // MockDataSeeder2::class,    --> A
        // MockSeederEstresante::class,    --> A
        // TestSeeder::class,       --> S

    ]);
    }


}
