<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Blog',
                'slug' => 'blog',
                'description' => 'Manajemen artikel dan konten blog',
                'icon' => 'fas fa-blog',
                'color' => '#3B82F6',
                'sort_order' => 1,
                'config' => [
                    'features' => ['posts', 'categories', 'tags', 'comments'],
                    'permissions' => ['create', 'read', 'update', 'delete']
                ]
            ],
            [
                'name' => 'CRM',
                'slug' => 'crm',
                'description' => 'Customer Relationship Management',
                'icon' => 'fas fa-users',
                'color' => '#10B981',
                'sort_order' => 2,
                'config' => [
                    'features' => ['contacts', 'leads', 'deals', 'activities'],
                    'permissions' => ['create', 'read', 'update', 'delete']
                ]
            ],
            [
                'name' => 'Inventory',
                'slug' => 'inventory',
                'description' => 'Manajemen stok dan produk',
                'icon' => 'fas fa-boxes',
                'color' => '#F59E0B',
                'sort_order' => 3,
                'config' => [
                    'features' => ['products', 'categories', 'stock', 'suppliers'],
                    'permissions' => ['create', 'read', 'update', 'delete']
                ]
            ],
            [
                'name' => 'Analytics',
                'slug' => 'analytics',
                'description' => 'Dashboard analitik dan laporan',
                'icon' => 'fas fa-chart-bar',
                'color' => '#8B5CF6',
                'sort_order' => 4,
                'config' => [
                    'features' => ['dashboard', 'reports', 'charts', 'exports'],
                    'permissions' => ['read']
                ]
            ],
            [
                'name' => 'Support',
                'slug' => 'support',
                'description' => 'Sistem tiket support pelanggan',
                'icon' => 'fas fa-headset',
                'color' => '#EF4444',
                'sort_order' => 5,
                'config' => [
                    'features' => ['tickets', 'knowledge_base', 'chat', 'feedback'],
                    'permissions' => ['create', 'read', 'update', 'delete']
                ]
            ]
        ];

        foreach ($modules as $moduleData) {
            \App\Models\Module::create($moduleData);
        }
    }
}
