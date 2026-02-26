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

        $tenantId = 'zainab_center';
        $tenant = \App\Models\Tenant::find($tenantId);

        if (!$tenant) {
            try {
                $tenant = \App\Models\Tenant::create([
                    'id' => $tenantId,
                ]);

                $tenant->domains()->create([
                    'domain' => 'localhost',
                ]);

                $tenant->domains()->create([
                    'domain' => 'zianabcen-vxv3dncn.on-forge.com',
                ]);
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'already exists')) {
                    $tenant = \App\Models\Tenant::withoutEvents(function () use ($tenantId) {
                        return \App\Models\Tenant::create([
                            'id' => $tenantId,
                        ]);
                    });

                    $tenant->domains()->firstOrCreate([
                        'domain' => 'localhost',
                    ]);

                    $tenant->domains()->firstOrCreate([
                        'domain' => 'zianabcen-vxv3dncn.on-forge.com',
                    ]);
                } else {
                    throw $e;
                }
            }
        } else {
            // Ensure domains exist even if tenant record was already there
            $tenant->domains()->firstOrCreate(['domain' => 'localhost']);
            $tenant->domains()->firstOrCreate(['domain' => 'zianabcen-vxv3dncn.on-forge.com']);
        }

        // Run the TenantSampleDataSeeder
        $tenant->run(function () {
            $this->call([
                \Database\Seeders\TenantSampleDataSeeder::class,
                \Database\Seeders\Tenant\ProgramsTableSeeder::class,
            ]);
        });
    }
}
