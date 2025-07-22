<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectAfterLogin();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'You have been logged out successfully.');
    }

    private function redirectAfterLogin()
    {
        $user = Auth::user();

        // SuperAdmin goes to admin panel
        if ($user->isSuperAdmin()) {
            return redirect('/admin/tenants');
        }

        // Regular users go to their tenant dashboard
        if ($user->tenant_id) {
            $tenant = $user->tenant;
            if ($tenant) {
                // Redirect to tenant dashboard with tenant parameter
                return redirect('/?tenant=' . $tenant->subdomain);
            }
        }

        // Fallback to login if no tenant found
        return redirect('/login')->withErrors(['email' => 'No tenant assigned to your account.']);
    }
}
