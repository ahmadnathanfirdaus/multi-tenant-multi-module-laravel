<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Management - Multitenant App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl font-bold text-gray-900">SuperAdmin Panel</h1>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="/admin/tenants" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-building mr-2"></i>Tenants
                                </a>
                                <a href="/admin/modules" class="bg-blue-500 text-white px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-puzzle-piece mr-2"></i>Modules
                                </a>
                                <a href="/admin/tenant-modules" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-cogs mr-2"></i>Tenant Modules
                                </a>
                                <a href="/admin/users" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-users mr-2"></i>Users
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-700 text-sm mr-4">
                            <i class="fas fa-user-shield mr-1"></i>
                            {{ auth()->user()->name ?? 'SuperAdmin' }}
                        </span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @livewire('module-manager')
        </main>
    </div>

    @livewireScripts
</body>
</html>
