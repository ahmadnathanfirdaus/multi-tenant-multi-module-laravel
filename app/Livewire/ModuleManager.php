<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

class ModuleManager extends Component
{
    use WithPagination;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $icon = '';
    public $color = '#3B82F6';
    public $is_active = true;
    public $sort_order = 0;
    public $editingModuleId = null;
    public $showForm = false;

    protected $rules = [
        'name' => 'required|min:3',
        'slug' => 'required|min:3|alpha_dash',
        'description' => 'nullable|string',
        'icon' => 'nullable|string',
        'color' => 'required|string',
        'is_active' => 'boolean',
        'sort_order' => 'integer|min:0',
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
        $this->reset(['name', 'slug', 'description', 'icon', 'color', 'is_active', 'sort_order', 'editingModuleId']);
        $this->color = '#3B82F6';
        $this->is_active = true;
        $this->sort_order = 0;
        $this->showForm = true;
    }

    public function editModule($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $this->name = $module->name;
        $this->slug = $module->slug;
        $this->description = $module->description;
        $this->icon = $module->icon;
        $this->color = $module->color;
        $this->is_active = $module->is_active;
        $this->sort_order = $module->sort_order;
        $this->editingModuleId = $moduleId;
        $this->showForm = true;
    }

    public function saveModule()
    {
        if ($this->editingModuleId) {
            $this->validate([
                'name' => 'required|min:3',
                'slug' => 'required|min:3|alpha_dash|unique:modules,slug,' . $this->editingModuleId,
                'description' => 'nullable|string',
                'icon' => 'nullable|string',
                'color' => 'required|string',
                'is_active' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);

            $module = Module::findOrFail($this->editingModuleId);
            $module->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'icon' => $this->icon,
                'color' => $this->color,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);
            session()->flash('message', 'Module berhasil diperbarui!');
        } else {
            $this->validate([
                'name' => 'required|min:3',
                'slug' => 'required|min:3|alpha_dash|unique:modules,slug',
                'description' => 'nullable|string',
                'icon' => 'nullable|string',
                'color' => 'required|string',
                'is_active' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);

            Module::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'icon' => $this->icon,
                'color' => $this->color,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);
            session()->flash('message', 'Module berhasil dibuat!');
        }

        $this->reset(['name', 'slug', 'description', 'icon', 'color', 'is_active', 'sort_order', 'editingModuleId', 'showForm']);
    }

    public function deleteModule($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $module->delete();
        session()->flash('message', 'Module berhasil dihapus!');
    }

    public function toggleModuleStatus($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $module->update(['is_active' => !$module->is_active]);
        session()->flash('message', 'Status module berhasil diubah!');
    }

    public function render()
    {
        return view('livewire.module-manager', [
            'modules' => Module::ordered()->paginate(10)
        ]);
    }
}
