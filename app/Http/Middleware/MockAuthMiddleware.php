<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MockAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Mock login based on route
            if ($request->is('admin/*')) {
                // Login as superadmin for admin routes
                $user = User::where('role', 'superadmin')->first();
            } else {
                // Login as admin for tenant routes
                $tenantParam = $request->get('tenant', 'demo');
                $user = User::whereHas('tenant', function($query) use ($tenantParam) {
                    $query->where('subdomain', $tenantParam);
                })->where('role', 'admin')->first();
            }

            if ($user) {
                Auth::login($user);
            }
        }

        return $next($request);
    }
}
