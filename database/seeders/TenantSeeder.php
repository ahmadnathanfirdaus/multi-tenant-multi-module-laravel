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
        // Create superadmin (no tenant)
        \App\Models\User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'tenant_id' => null,
        ]);

        $tenants = [
            [
                'name' => 'Demo Company',
                'subdomain' => 'demo',
                'data' => ['theme' => 'blue', 'features' => ['posts', 'users']],
                'modules' => ['blog', 'crm', 'analytics'] // Enable blog, crm, and analytics
            ],
            [
                'name' => 'Test Corporation',
                'subdomain' => 'test',
                'data' => ['theme' => 'green', 'features' => ['posts']],
                'modules' => ['blog', 'inventory', 'support'] // Enable blog, inventory, and support
            ],
            [
                'name' => 'Sample Inc',
                'subdomain' => 'sample',
                'data' => ['theme' => 'red', 'features' => ['posts', 'analytics']],
                'modules' => ['crm', 'inventory', 'analytics', 'support'] // Enable all except blog
            ],
        ];

        foreach ($tenants as $tenantData) {
            $modules = $tenantData['modules'] ?? [];
            unset($tenantData['modules']); // Remove modules from tenant data

            $tenant = \App\Models\Tenant::create($tenantData);

            // Assign modules to tenant
            if (!empty($modules)) {
                $moduleIds = \App\Models\Module::whereIn('slug', $modules)->pluck('id');
                foreach ($moduleIds as $moduleId) {
                    $tenant->modules()->attach($moduleId, [
                        'is_enabled' => true,
                        'settings' => null
                    ]);
                }
            }

            // Create admin for each tenant
            \App\Models\User::create([
                'name' => 'Admin ' . $tenant->name,
                'email' => 'admin@' . $tenant->subdomain . '.com',
                'password' => bcrypt('password'),
                'tenant_id' => $tenant->id,
                'role' => 'admin',
            ]);

            // Create regular users for each tenant
            \App\Models\User::create([
                'name' => 'User ' . $tenant->name,
                'email' => 'user@' . $tenant->subdomain . '.com',
                'password' => bcrypt('password'),
                'tenant_id' => $tenant->id,
                'role' => 'user',
            ]);

            // Create sample data for enabled modules
            $this->createSampleDataForTenant($tenant, $modules);
        }
    }

    private function createSampleDataForTenant($tenant, $modules)
    {
        // Get admin user for this tenant
        $adminUser = \App\Models\User::where('tenant_id', $tenant->id)
                                    ->where('role', 'admin')
                                    ->first();

        if (!$adminUser) return;

        // Temporarily set tenant context for creating sample data
        app()->instance('tenant', $tenant);

        foreach ($modules as $moduleSlug) {
            switch ($moduleSlug) {
                case 'blog':
                    $this->createSampleBlogPosts($adminUser);
                    break;
                case 'crm':
                    $this->createSampleContacts($adminUser);
                    break;
                case 'inventory':
                    $this->createSampleProducts($adminUser);
                    break;
                case 'support':
                    $this->createSampleTickets($adminUser);
                    break;
            }
        }
    }

    private function createSampleBlogPosts($user)
    {
        $tenant = app('tenant');
        $tenantName = $tenant->name;

        $posts = [
            [
                'title' => "Welcome to {$tenantName} Blog",
                'excerpt' => "This is our first blog post to welcome you to {$tenantName} platform.",
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ],
            [
                'title' => "Getting Started with {$tenantName}",
                'excerpt' => "Learn how to make the most of {$tenantName} platform with this comprehensive guide.",
                'content' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ],
            [
                'title' => "{$tenantName} Draft Post",
                'excerpt' => "This is a draft post that is not yet published for {$tenantName}.",
                'content' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
                'status' => 'draft',
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ],
        ];

        foreach ($posts as $postData) {
            \App\Models\BlogPost::create($postData);
        }
    }

    private function createSampleContacts($user)
    {
        $tenant = app('tenant');
        $tenantCode = strtolower($tenant->subdomain);

        $contacts = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => "john.doe@{$tenantCode}-example.com",
                'phone' => '+1234567890',
                'company' => 'Acme Corp',
                'position' => 'CEO',
                'status' => 'customer',
                'deal_value' => 50000.00,
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => "jane.smith@{$tenantCode}-example.com",
                'phone' => '+1234567891',
                'company' => 'Tech Solutions',
                'position' => 'CTO',
                'status' => 'lead',
                'deal_value' => 25000.00,
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ],
        ];

        foreach ($contacts as $contactData) {
            \App\Models\CrmContact::create($contactData);
        }
    }

    private function createSampleProducts($user)
    {
        $tenant = app('tenant');
        $tenantCode = strtoupper(substr($tenant->subdomain, 0, 3));

        $products = [
            [
                'name' => 'Laptop Computer',
                'sku' => "{$tenantCode}-LAP-001",
                'description' => 'High-performance laptop for business use',
                'category' => 'Electronics',
                'price' => 1299.99,
                'cost' => 800.00,
                'stock_quantity' => 25,
                'min_stock_level' => 5,
                'unit' => 'pcs',
                'supplier' => 'Tech Supplier Inc',
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ],
            [
                'name' => 'Office Chair',
                'sku' => "{$tenantCode}-CHR-001",
                'description' => 'Ergonomic office chair with lumbar support',
                'category' => 'Furniture',
                'price' => 299.99,
                'cost' => 150.00,
                'stock_quantity' => 10,
                'min_stock_level' => 2,
                'unit' => 'pcs',
                'supplier' => 'Furniture Plus',
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ],
        ];

        foreach ($products as $productData) {
            \App\Models\InventoryProduct::create($productData);
        }
    }

    private function createSampleTickets($user)
    {
        $tenant = app('tenant');
        $tenantCode = strtoupper(substr($tenant->subdomain, 0, 3));

        $tickets = [
            [
                'ticket_number' => "{$tenantCode}-TKT-001",
                'subject' => 'Login Issues',
                'description' => 'User is unable to login to their account',
                'priority' => 'high',
                'status' => 'open',
                'category' => 'Technical',
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ],
            [
                'ticket_number' => "{$tenantCode}-TKT-002",
                'subject' => 'Feature Request',
                'description' => 'Request for new dashboard feature',
                'priority' => 'medium',
                'status' => 'in_progress',
                'category' => 'Enhancement',
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ],
        ];

        foreach ($tickets as $ticketData) {
            \App\Models\SupportTicket::create($ticketData);
        }
    }
}
