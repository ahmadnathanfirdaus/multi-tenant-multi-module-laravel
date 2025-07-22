<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share enabled modules with specific views only to avoid infinite loops
        View::composer(['components.layouts.app', 'livewire.module-dashboard'], function ($view) {
            $enabledModules = collect();

            try {
                if (app()->bound('tenant')) {
                    $tenant = app('tenant');
                    $enabledModules = $tenant->enabledModules()
                                            ->where('modules.is_active', true)
                                            ->ordered()
                                            ->get();
                }
            } catch (\Exception) {
                // Handle any errors gracefully to prevent infinite loops
                $enabledModules = collect();
            }

            $view->with('enabledModules', $enabledModules);
        });
    }
}
