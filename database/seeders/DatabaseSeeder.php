<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Données de démonstration complètes : php artisan migrate:fresh --seed
     * Comptes : admin@example.com, company@example.com, candidate@example.com (mot de passe : DemoDataSeeder::PASSWORD)
     */
    public function run(): void
    {
        $this->call(DemoDataSeeder::class);
    }
}
