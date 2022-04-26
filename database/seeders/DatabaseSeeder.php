<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create([
            'role' => 'developer',
            'name' => 'Developer',
            'email' => 'developer@example.com',
        ]);
        \App\Models\User::factory(1)->create([
            'role' => 'admin',
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        \App\Models\User::factory(8)->create();
    }
}
