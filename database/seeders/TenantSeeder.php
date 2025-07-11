<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = [
            [
                'name' => 'Demo Company',
                'subdomain' => 'demo',
                'data' => ['theme' => 'blue', 'features' => ['posts', 'users']],
            ],
            [
                'name' => 'Test Corporation',
                'subdomain' => 'test',
                'data' => ['theme' => 'green', 'features' => ['posts']],
            ],
            [
                'name' => 'Sample Inc',
                'subdomain' => 'sample',
                'data' => ['theme' => 'red', 'features' => ['posts', 'analytics']],
            ],
        ];

        foreach ($tenants as $tenantData) {
            $tenant = \App\Models\Tenant::create($tenantData);

            // Create a default user for each tenant
            \App\Models\User::create([
                'name' => 'Admin ' . $tenant->name,
                'email' => 'admin@' . $tenant->subdomain . '.com',
                'password' => bcrypt('password'),
                'tenant_id' => $tenant->id,
            ]);
        }
    }
}
