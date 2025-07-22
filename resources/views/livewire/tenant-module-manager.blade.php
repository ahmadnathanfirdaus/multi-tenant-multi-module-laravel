<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Tenant Module Management</h2>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tenant Selection -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-md rounded p-6">
                <h3 class="text-lg font-semibold mb-4">Pilih Tenant</h3>

                <div class="mb-4">
                    <input wire:model.live="search" type="text" placeholder="Cari tenant..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach ($tenants as $tenant)
                        <div wire:click="selectTenant({{ $tenant->id }})"
                             class="p-3 border rounded cursor-pointer hover:bg-gray-50 {{ $selectedTenant && $selectedTenant->id === $tenant->id ? 'bg-blue-50 border-blue-500' : 'border-gray-200' }}">
                            <div class="font-medium">{{ $tenant->name }}</div>
                            <div class="text-sm text-gray-500">{{ $tenant->subdomain }}</div>
                            <div class="text-xs text-gray-400">{{ $tenant->enabledModules->count() }} modules aktif</div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $tenants->links() }}
                </div>
            </div>
        </div>

        <!-- Module Management -->
        <div class="lg:col-span-2">
            @if ($selectedTenant)
                <div class="bg-white shadow-md rounded p-6">
                    <h3 class="text-lg font-semibold mb-4">
                        Modules untuk {{ $selectedTenant->name }}
                        <span class="text-sm text-gray-500">({{ $selectedTenant->subdomain }})</span>
                    </h3>

                    <div class="space-y-4">
                        @foreach ($modules as $module)
                            @php
                                $tenantModule = $tenantModules->get($module->id);
                                $isAssigned = $tenantModule !== null;
                                $isEnabled = $isAssigned && $tenantModule->pivot->is_enabled;
                            @endphp

                            <div class="border rounded-lg p-4 {{ $isEnabled ? 'bg-green-50 border-green-200' : ($isAssigned ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50 border-gray-200') }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if($module->icon)
                                            <i class="{{ $module->icon }} mr-3 text-2xl" style="color: {{ $module->color }}"></i>
                                        @endif
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $module->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $module->description }}</p>
                                            <div class="flex items-center mt-1">
                                                <span class="text-xs px-2 py-1 rounded {{ $module->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $module->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                                @if($isAssigned)
                                                    <span class="ml-2 text-xs px-2 py-1 rounded {{ $isEnabled ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $isEnabled ? 'Diaktifkan' : 'Dinonaktifkan' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        @if($module->is_active)
                                            <button wire:click="toggleModuleForTenant({{ $module->id }})"
                                                    class="px-3 py-1 text-sm rounded {{ $isEnabled ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }}">
                                                {{ $isEnabled ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>

                                            @if($isAssigned)
                                                <button wire:click="removeModuleFromTenant({{ $module->id }})"
                                                        class="px-3 py-1 text-sm bg-red-500 hover:bg-red-600 text-white rounded"
                                                        onclick="return confirm('Yakin ingin menghapus module ini dari tenant?')">
                                                    Hapus
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-500">Module tidak aktif</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white shadow-md rounded p-6 text-center">
                    <div class="text-gray-500">
                        <i class="fas fa-arrow-left text-4xl mb-4"></i>
                        <p class="text-lg">Pilih tenant untuk mengelola modules</p>
                        <p class="text-sm">Klik pada salah satu tenant di sebelah kiri untuk mulai mengelola modules mereka.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
