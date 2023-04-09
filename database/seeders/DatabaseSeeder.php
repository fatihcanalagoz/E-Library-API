<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use App\Models\Depart;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {   User::insert([
        'name' => 'zank',
        'email' => 'admin@zank',
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'email_verified_at' => now(),
        'type' => 'worker',
        'remember_token' => Str::random(10)
    ]);
         User::factory(2)->create();
         Depart::factory(3)->create();
         Book::factory(10)->create();
    }
}
