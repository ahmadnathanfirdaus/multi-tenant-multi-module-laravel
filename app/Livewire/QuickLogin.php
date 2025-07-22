<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class QuickLogin extends Component
{
    public function quickLogin($email)
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);

            // Redirect based on user role
            if ($user->isSuperAdmin()) {
                return redirect('/admin/tenants');
            } elseif ($user->tenant_id) {
                $tenant = $user->tenant;
                if ($tenant) {
                    return redirect('/?tenant=' . $tenant->subdomain);
                }
            }
        }

        session()->flash('error', 'Quick login failed.');
        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.quick-login');
    }
}
