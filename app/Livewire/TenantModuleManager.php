<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Module;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class TenantModuleManager extends Component
{
    use WithPagination;

    public $selectedTenant = null;
    public $search = '';

    public function mount()
    {
        // Check if user is superadmin
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function selectTenant($tenantId)
    {
        $this->selectedTenant = Tenant::findOrFail($tenantId);
        $this->resetPage();
    }

    public function toggleModuleForTenant($moduleId)
    {
        if (!$this->selectedTenant) {
            session()->flash('error', 'Pilih tenant terlebih dahulu!');
            return;
        }

        $module = Module::findOrFail($moduleId);

        // Check if module is already assigned to tenant
        $existingAssignment = $this->selectedTenant->modules()->where('module_id', $moduleId)->first();

        if ($existingAssignment) {
            // Toggle the enabled status
            $this->selectedTenant->modules()->updateExistingPivot($moduleId, [
                'is_enabled' => !$existingAssignment->pivot->is_enabled
            ]);
            $status = $existingAssignment->pivot->is_enabled ? 'dinonaktifkan' : 'diaktifkan';
        } else {
            // Assign module to tenant
            $this->selectedTenant->modules()->attach($moduleId, [
                'is_enabled' => true,
                'settings' => null
            ]);
            $status = 'diaktifkan';
        }

        session()->flash('message', "Module {$module->name} berhasil {$status} untuk {$this->selectedTenant->name}!");
    }

    public function removeModuleFromTenant($moduleId)
    {
        if (!$this->selectedTenant) {
            session()->flash('error', 'Pilih tenant terlebih dahulu!');
            return;
        }

        $module = Module::findOrFail($moduleId);
        $this->selectedTenant->modules()->detach($moduleId);

        session()->flash('message', "Module {$module->name} berhasil dihapus dari {$this->selectedTenant->name}!");
    }

    public function render()
    {
        $tenants = Tenant::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('subdomain', 'like', '%' . $this->search . '%');
        })->paginate(10);

        $modules = Module::active()->ordered()->get();

        $tenantModules = [];
        if ($this->selectedTenant) {
            $tenantModules = $this->selectedTenant->modules()
                                                 ->withPivot('is_enabled', 'settings')
                                                 ->get()
                                                 ->keyBy('id');
        }

        return view('livewire.tenant-module-manager', [
            'tenants' => $tenants,
            'modules' => $modules,
            'tenantModules' => $tenantModules
        ]);
    }
}
