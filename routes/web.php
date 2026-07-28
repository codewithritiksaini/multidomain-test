<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TenantAdminController;
use App\Http\Controllers\PublicBlogController;

$centralDomain = env('APP_CENTRAL_DOMAIN', 'localhost');

// =========================================================================
// 1. TENANT / SUBDOMAIN ROUTES ({subdomain}.yourdomain.com)
// =========================================================================
Route::domain('{subdomain}.' . $centralDomain)->middleware(['identify.tenant'])->group(function () {

    // A. Public Blog Frontend (Shows only this subdomain's blogs)
    Route::get('/', [PublicBlogController::class, 'index'])->name('tenant.public.home');
    Route::get('/blog/{slug}', [PublicBlogController::class, 'show'])->name('tenant.public.single');

    // B. Tenant Admin Login (http://{subdomain}.yourdomain.com/login)
    Route::get('/login', [TenantAdminController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [TenantAdminController::class, 'login']);
    Route::post('/logout', [TenantAdminController::class, 'logout'])->name('tenant.admin.logout');

    // C. Tenant Admin Dashboard & Blog Management
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [TenantAdminController::class, 'dashboard'])->name('tenant.admin.dashboard');
        Route::get('/blogs/create', [TenantAdminController::class, 'createBlog'])->name('tenant.admin.blogs.create');
        Route::post('/blogs', [TenantAdminController::class, 'storeBlog'])->name('tenant.admin.blogs.store');
    });
});

// =========================================================================
// 2. CENTRAL / SUPERADMIN ROUTES (Direct Main Domain: yourdomain.com, localhost, 127.0.0.1)
// =========================================================================
Route::get('/', [SuperAdminController::class, 'showLoginForm'])->name('central.home');
Route::get('/login', [SuperAdminController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SuperAdminController::class, 'login']);
Route::post('/logout', [SuperAdminController::class, 'logout'])->name('superadmin.logout');

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/admins/create', [SuperAdminController::class, 'createAdmin'])->name('superadmin.admins.create');
    Route::post('/admins', [SuperAdminController::class, 'storeAdmin'])->name('superadmin.admins.store');
});
