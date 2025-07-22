<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

class ModuleAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $moduleSlug
     */
    public function handle(Request $request, Closure $next, string $moduleSlug): Response
    {
        // Skip for superadmin
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return $next($request);
        }

        // Check if tenant context exists
        if (!app()->bound('tenant')) {
            abort(404, 'Tenant not found');
        }

        $tenant = app('tenant');

        // Find the module
        $module = Module::where('slug', $moduleSlug)->where('is_active', true)->first();

        if (!$module) {
            abort(404, 'Module not found or inactive');
        }

        // Check if tenant has access to this module
        $hasAccess = $tenant->modules()
                           ->where('module_id', $module->id)
                           ->wherePivot('is_enabled', true)
                           ->exists();

        if (!$hasAccess) {
            abort(403, 'Access denied. This module is not enabled for your tenant.');
        }

        // Store module in app instance for use in controllers/views
        app()->instance('current_module', $module);

        return $next($request);
    }
}
