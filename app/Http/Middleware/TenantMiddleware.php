<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        // Extract subdomain from host
        $subdomain = explode('.', $host)[0];

        // Skip tenant resolution for localhost or non-subdomain requests
        if ($host === 'localhost' || $host === '127.0.0.1' || !str_contains($host, '.')) {
            // For development, we can use a query parameter or default tenant
            $subdomain = $request->get('tenant', 'demo');
        }

        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Store tenant in app instance for global access
        app()->instance('tenant', $tenant);

        // Set default tenant context for models
        config(['app.tenant_id' => $tenant->id]);

        return $next($request);
    }
}
