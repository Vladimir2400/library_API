<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Librarian;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $this->call([
            BooksTableSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => $faker->unique()->safeEmail,
        ]);
        Librarian::factory()->create([
            'name' => 'Test Librarian',
            'email' => $faker->unique()->safeEmail,
        ]);
    }
}