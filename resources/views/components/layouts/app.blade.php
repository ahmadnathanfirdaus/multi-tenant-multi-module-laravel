<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Multi-Tenant App' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-gray-100">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">
                            Multi-Tenant Laravel App
                        </h1>
                    </div>

                    <!-- Navigation Menu -->
                    <div class="flex items-center space-x-6">
                        @if(request()->is('admin/*'))
                            <!-- Super Admin Menu -->
                            <a href="/admin/tenants" class="text-sm {{ request()->is('admin/tenants') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Tenants
                            </a>
                            <a href="/admin/modules" class="text-sm {{ request()->is('admin/modules') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Modules
                            </a>
                            <a href="/admin/tenant-modules" class="text-sm {{ request()->is('admin/tenant-modules') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Tenant Modules
                            </a>
                            <a href="/admin/users" class="text-sm {{ request()->is('admin/users') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                All Users
                            </a>
                        @else
                            <!-- Tenant Menu -->
                            <a href="/" class="text-sm {{ request()->is('/') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                <i class="fas fa-home mr-1"></i>Dashboard
                            </a>

                            <!-- Dynamic Module Menu -->
                            @if(isset($enabledModules) && $enabledModules->count() > 0)
                                @foreach($enabledModules as $module)
                                    <a href="/modules/{{ $module->slug }}" class="text-sm {{ request()->is('modules/' . $module->slug . '*') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                        @if($module->icon)
                                            <i class="{{ $module->icon }} mr-1" style="color: {{ $module->color }}"></i>
                                        @endif
                                        {{ $module->name }}
                                    </a>
                                @endforeach
                            @endif

                            <a href="/users" class="text-sm {{ request()->is('users') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                <i class="fas fa-users mr-1"></i>Users
                            </a>
                        @endif

                        @if(app()->bound('tenant'))
                            <span class="text-sm text-gray-600">
                                Tenant: <strong>{{ app('tenant')->name }}</strong>
                            </span>
                        @endif

                        @if(!request()->is('admin/*') && app()->bound('tenant'))
                            <a href="/admin/tenants" class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">
                                Super Admin
                            </a>
                        @endif

                        @auth
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ auth()->user()->name }}
                                    <span class="text-xs text-gray-500">({{ ucfirst(auth()->user()->role) }})</span>
                                </span>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                        <i class="fas fa-sign-out-alt mr-1"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>

        @livewireScripts
    </body>
</html>
