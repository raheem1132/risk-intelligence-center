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
        // Memanggil InitialDataSeeder yang baru saja kamu isi
        $this->call([
            InitialDataSeeder::class,
        ]);
    }
}