<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class TenantManager extends Component
{
    use WithPagination;

    public $name = '';
    public $subdomain = '';
    public $editingTenantId = null;
    public $showForm = false;

    protected $rules = [
        'name' => 'required|min:3',
        'subdomain' => 'required|min:3|unique:tenants,subdomain|alpha_dash',
    ];

    public function mount()
    {
        // Check if user is superadmin
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function showCreateForm()
    {
        $this->reset(['name', 'subdomain', 'editingTenantId']);
        $this->showForm = true;
    }

    public function editTenant($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $this->name = $tenant->name;
        $this->subdomain = $tenant->subdomain;
        $this->editingTenantId = $tenantId;
        $this->showForm = true;
    }

    public function saveTenant()
    {
        if ($this->editingTenantId) {
            $this->validate([
                'name' => 'required|min:3',
                'subdomain' => 'required|min:3|alpha_dash|unique:tenants,subdomain,' . $this->editingTenantId,
            ]);

            $tenant = Tenant::findOrFail($this->editingTenantId);
            $tenant->update([
                'name' => $this->name,
                'subdomain' => $this->subdomain,
            ]);
            session()->flash('message', 'Tenant berhasil diperbarui!');
        } else {
            $this->validate();

            $tenant = Tenant::create([
                'name' => $this->name,
                'subdomain' => $this->subdomain,
                'data' => ['theme' => 'blue', 'features' => ['posts', 'users']],
            ]);

            // Create default admin for new tenant
            User::create([
                'name' => 'Admin ' . $tenant->name,
                'email' => 'admin@' . $tenant->subdomain . '.com',
                'password' => bcrypt('password'),
                'tenant_id' => $tenant->id,
                'role' => 'admin',
            ]);

            session()->flash('message', 'Tenant berhasil dibuat!');
        }

        $this->reset(['name', 'subdomain', 'editingTenantId', 'showForm']);
    }

    public function deleteTenant($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $tenant->delete(); // This will cascade delete users and posts
        session()->flash('message', 'Tenant berhasil dihapus!');
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'subdomain', 'editingTenantId', 'showForm']);
    }

    public function render()
    {
        $tenants = Tenant::withCount(['users'])->latest()->paginate(10);

        return view('livewire.tenant-manager', [
            'tenants' => $tenants,
        ]);
    }
}
