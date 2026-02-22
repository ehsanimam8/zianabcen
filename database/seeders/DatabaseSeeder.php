<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $tenant = \App\Models\Tenant::create([
            'id' => 'zainab_center',
        ]);

        $tenant->domains()->create([
            'domain' => 'localhost',
        ]);

        // Run the TenantSampleDataSeeder
        $tenant->run(function () {
            $this->call([
                \Database\Seeders\TenantSampleDataSeeder::class,
                \Database\Seeders\Tenant\ProgramsTableSeeder::class,
            ]);
        });
    }
}
