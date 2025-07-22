<?php

namespace App\Livewire;

use Livewire\Component;

class ModuleDashboard extends Component
{
    public function render()
    {
        $enabledModules = collect();
        $moduleStats = [];

        if (app()->bound('tenant')) {
            $tenant = app('tenant');
            $enabledModules = $tenant->enabledModules()
                                    ->where('modules.is_active', true)
                                    ->ordered()
                                    ->get();

            // Get some basic stats for each module
            foreach ($enabledModules as $module) {
                $stats = $this->getModuleStats($module);
                $moduleStats[$module->slug] = $stats;
            }
        }

        return view('livewire.module-dashboard', [
            'enabledModules' => $enabledModules,
            'moduleStats' => $moduleStats
        ]);
    }

    private function getModuleStats($module)
    {
        $stats = ['count' => 0, 'recent' => 0];

        try {
            switch ($module->slug) {
                case 'blog':
                    $stats['count'] = \App\Models\BlogPost::count();
                    $stats['recent'] = \App\Models\BlogPost::where('created_at', '>=', now()->subDays(7))->count();
                    break;
                case 'crm':
                    $stats['count'] = \App\Models\CrmContact::count();
                    $stats['recent'] = \App\Models\CrmContact::where('created_at', '>=', now()->subDays(7))->count();
                    break;
                case 'inventory':
                    $stats['count'] = \App\Models\InventoryProduct::count();
                    $stats['recent'] = \App\Models\InventoryProduct::where('created_at', '>=', now()->subDays(7))->count();
                    break;
                case 'support':
                    $stats['count'] = \App\Models\SupportTicket::count();
                    $stats['recent'] = \App\Models\SupportTicket::where('created_at', '>=', now()->subDays(7))->count();
                    break;
            }
        } catch (\Exception) {
            // Handle any errors gracefully
        }

        return $stats;
    }
}
