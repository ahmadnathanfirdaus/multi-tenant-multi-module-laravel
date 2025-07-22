<div class="space-y-2">
    <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Login (Demo):</h4>

    <button wire:click="quickLogin('superadmin@example.com')"
            class="w-full text-left bg-red-50 hover:bg-red-100 border border-red-200 rounded p-3 transition-colors">
        <div class="text-sm">
            <strong class="text-red-600">SuperAdmin</strong><br>
            <span class="text-gray-600">superadmin@example.com</span>
        </div>
    </button>

    <button wire:click="quickLogin('admin@demo.com')"
            class="w-full text-left bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded p-3 transition-colors">
        <div class="text-sm">
            <strong class="text-blue-600">Demo Company Admin</strong><br>
            <span class="text-gray-600">admin@demo.com</span>
        </div>
    </button>

    <button wire:click="quickLogin('admin@test.com')"
            class="w-full text-left bg-green-50 hover:bg-green-100 border border-green-200 rounded p-3 transition-colors">
        <div class="text-sm">
            <strong class="text-green-600">Test Corporation Admin</strong><br>
            <span class="text-gray-600">admin@test.com</span>
        </div>
    </button>

    <button wire:click="quickLogin('admin@sample.com')"
            class="w-full text-left bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded p-3 transition-colors">
        <div class="text-sm">
            <strong class="text-purple-600">Sample Inc Admin</strong><br>
            <span class="text-gray-600">admin@sample.com</span>
        </div>
    </button>

    <button wire:click="quickLogin('user@demo.com')"
            class="w-full text-left bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded p-3 transition-colors">
        <div class="text-sm">
            <strong class="text-gray-600">Demo User</strong><br>
            <span class="text-gray-600">user@demo.com</span>
        </div>
    </button>
</div>
