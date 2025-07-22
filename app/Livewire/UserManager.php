<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManager extends Component
{
    use WithPagination;

    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'user';
    public $editingUserId = null;
    public $showForm = false;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'role' => 'required|in:user,admin',
    ];

    public function mount()
    {
        // Check if user can manage users (superadmin or admin)
        if (!Auth::check() || !Auth::user()->canManageUsers()) {
            abort(403, 'Unauthorized');
        }
    }

    public function showCreateForm()
    {
        $this->reset(['name', 'email', 'password', 'role', 'editingUserId']);
        $this->role = 'user';
        $this->showForm = true;
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);

        // Only allow editing users from same tenant (for admin) or any user (for superadmin)
        if (Auth::user()->isAdmin() && $user->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized');
        }

        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->editingUserId = $userId;
        $this->showForm = true;
    }

    public function saveUser()
    {
        if ($this->editingUserId) {
            $this->validate([
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email,' . $this->editingUserId,
                'role' => 'required|in:user,admin',
            ]);

            $user = User::findOrFail($this->editingUserId);

            // Only allow editing users from same tenant (for admin) or any user (for superadmin)
            if (Auth::user()->isAdmin() && $user->tenant_id !== Auth::user()->tenant_id) {
                abort(403, 'Unauthorized');
            }

            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ];

            if (!empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            $user->update($updateData);
            session()->flash('message', 'User berhasil diperbarui!');
        } else {
            $this->validate();

            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ];

            // Set tenant_id based on current user role
            if (Auth::user()->isSuperAdmin()) {
                // For superadmin, use current tenant context if available
                $userData['tenant_id'] = app()->bound('tenant') ? app('tenant')->id : null;
            } else {
                // For admin, always use their tenant
                $userData['tenant_id'] = Auth::user()->tenant_id;
            }

            User::create($userData);
            session()->flash('message', 'User berhasil dibuat!');
        }

        $this->reset(['name', 'email', 'password', 'role', 'editingUserId', 'showForm']);
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // Only allow deleting users from same tenant (for admin) or any user (for superadmin)
        if (Auth::user()->isAdmin() && $user->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized');
        }

        // Prevent deleting self
        if ($user->id === Auth::id()) {
            session()->flash('error', 'Tidak dapat menghapus akun sendiri!');
            return;
        }

        $user->delete();
        session()->flash('message', 'User berhasil dihapus!');
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'email', 'password', 'role', 'editingUserId', 'showForm']);
    }

    public function render()
    {
        $query = User::with('tenant');

        // Filter users based on current user role
        if (Auth::user()->isAdmin()) {
            // Admin can only see users from their tenant
            $query->where('tenant_id', Auth::user()->tenant_id);
        } elseif (Auth::user()->isSuperAdmin() && app()->bound('tenant')) {
            // Superadmin in tenant context sees only that tenant's users
            $query->where('tenant_id', app('tenant')->id);
        }
        // If superadmin not in tenant context, shows all users

        $users = $query->latest()->paginate(10);
        $currentTenant = app()->bound('tenant') ? app('tenant') : null;

        return view('livewire.user-manager', [
            'users' => $users,
            'currentTenant' => $currentTenant,
            'currentUser' => Auth::user(),
        ]);
    }
}
