<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });
});

    Route::get('/posts', function () {
        return view('posts');
    });

    Route::get('/users', function () {
        return view('users');
    });

    // Module routes with access control
    Route::prefix('modules')->group(function () {
    // Blog Module
    Route::middleware(['module.access:blog'])->prefix('blog')->name('modules.blog.')->group(function () {
        Route::get('/', [App\Http\Controllers\Modules\BlogController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Modules\BlogController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Modules\BlogController::class, 'store'])->name('store');
        Route::get('/{post}', [App\Http\Controllers\Modules\BlogController::class, 'show'])->name('show');
        Route::get('/{post}/edit', [App\Http\Controllers\Modules\BlogController::class, 'edit'])->name('edit');
        Route::put('/{post}', [App\Http\Controllers\Modules\BlogController::class, 'update'])->name('update');
        Route::delete('/{post}', [App\Http\Controllers\Modules\BlogController::class, 'destroy'])->name('destroy');
    });

    // CRM Module
    Route::middleware(['module.access:crm'])->prefix('crm')->name('modules.crm.')->group(function () {
        Route::get('/', [App\Http\Controllers\Modules\CrmController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Modules\CrmController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Modules\CrmController::class, 'store'])->name('store');
        Route::get('/{contact}', [App\Http\Controllers\Modules\CrmController::class, 'show'])->name('show');
        Route::get('/{contact}/edit', [App\Http\Controllers\Modules\CrmController::class, 'edit'])->name('edit');
        Route::put('/{contact}', [App\Http\Controllers\Modules\CrmController::class, 'update'])->name('update');
        Route::delete('/{contact}', [App\Http\Controllers\Modules\CrmController::class, 'destroy'])->name('destroy');
    });

    // Inventory Module
    Route::middleware(['module.access:inventory'])->prefix('inventory')->name('modules.inventory.')->group(function () {
        Route::get('/', [App\Http\Controllers\Modules\InventoryController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Modules\InventoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Modules\InventoryController::class, 'store'])->name('store');
        Route::get('/{product}', [App\Http\Controllers\Modules\InventoryController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [App\Http\Controllers\Modules\InventoryController::class, 'edit'])->name('edit');
        Route::put('/{product}', [App\Http\Controllers\Modules\InventoryController::class, 'update'])->name('update');
        Route::delete('/{product}', [App\Http\Controllers\Modules\InventoryController::class, 'destroy'])->name('destroy');
    });

    // Support Module
    Route::middleware(['module.access:support'])->prefix('support')->name('modules.support.')->group(function () {
        Route::get('/', [App\Http\Controllers\Modules\SupportController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Modules\SupportController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Modules\SupportController::class, 'store'])->name('store');
        Route::get('/{ticket}', [App\Http\Controllers\Modules\SupportController::class, 'show'])->name('show');
        Route::get('/{ticket}/edit', [App\Http\Controllers\Modules\SupportController::class, 'edit'])->name('edit');
        Route::put('/{ticket}', [App\Http\Controllers\Modules\SupportController::class, 'update'])->name('update');
        Route::delete('/{ticket}', [App\Http\Controllers\Modules\SupportController::class, 'destroy'])->name('destroy');
    });

    // Analytics Module
    Route::middleware(['module.access:analytics'])->prefix('analytics')->name('modules.analytics.')->group(function () {
        Route::get('/', [App\Http\Controllers\Modules\AnalyticsController::class, 'index'])->name('index');
    });
});

// Admin routes (not affected by tenant middleware)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/tenants', function () {
        return view('admin.tenants');
    });

    Route::get('/modules', function () {
        return view('admin.modules');
    });

    Route::get('/tenant-modules', function () {
        return view('admin.tenant-modules');
    });

    Route::get('/users', function () {
        return view('admin.users');
    });
});
