<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil UserSeeder untuk membuat data user default
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);

    }
}



