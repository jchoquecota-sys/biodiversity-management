<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar los seeders personalizados
        $this->call([
            UserSeeder::class,
            ConservationStatusSeeder::class,
            BiodiversityCategorySeeder::class,
            PublicationSeeder::class,
        ]);
    }
}
