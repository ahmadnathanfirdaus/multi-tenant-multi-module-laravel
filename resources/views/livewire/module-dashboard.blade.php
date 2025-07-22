<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        @if(app()->bound('tenant'))
            <p class="text-gray-600 mt-2">Welcome to {{ app('tenant')->name }}</p>
        @endif
    </div>

    @if($enabledModules->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($enabledModules as $module)
                @php
                    $stats = $moduleStats[$module->slug] ?? ['count' => 0, 'recent' => 0];
                @endphp

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                @if($module->icon)
                                    <div class="p-3 rounded-lg" style="background-color: {{ $module->color }}20;">
                                        <i class="{{ $module->icon }} text-2xl" style="color: {{ $module->color }}"></i>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $module->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $module->description }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $stats['count'] }}</div>
                                <div class="text-xs text-gray-500">Total Items</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold" style="color: {{ $module->color }}">{{ $stats['recent'] }}</div>
                                <div class="text-xs text-gray-500">This Week</div>
                            </div>
                        </div>

                        <a href="/modules/{{ $module->slug }}"
                           class="block w-full text-center py-2 px-4 rounded-md text-white font-medium hover:opacity-90 transition-opacity"
                           style="background-color: {{ $module->color }}">
                            Open {{ $module->name }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($enabledModules as $module)
                    <a href="/modules/{{ $module->slug }}/create"
                       class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        @if($module->icon)
                            <i class="{{ $module->icon }} mr-3" style="color: {{ $module->color }}"></i>
                        @endif
                        <span class="text-sm font-medium text-gray-700">
                            Create {{ $module->slug === 'blog' ? 'Post' : ($module->slug === 'crm' ? 'Contact' : ($module->slug === 'inventory' ? 'Product' : 'Ticket')) }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-puzzle-piece text-6xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">No Modules Available</h2>
            <p class="text-gray-600 mb-4">
                No modules have been enabled for your tenant yet. Contact your administrator to enable modules.
            </p>
            @if(auth()->user() && auth()->user()->canManageUsers())
                <a href="/users" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                    <i class="fas fa-users mr-2"></i>
                    Manage Users
                </a>
            @endif
        </div>
    @endif
</div>
