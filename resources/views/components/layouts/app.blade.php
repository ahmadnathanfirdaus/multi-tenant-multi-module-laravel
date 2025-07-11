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
                    <div class="flex items-center space-x-4">
                        @if(app()->bound('tenant'))
                            <span class="text-sm text-gray-600">
                                Tenant: <strong>{{ app('tenant')->name }}</strong>
                            </span>
                        @endif
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
