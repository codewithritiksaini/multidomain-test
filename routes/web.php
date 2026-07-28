<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TenantAdminController;
use App\Http\Controllers\PublicBlogController;
use App\Http\Middleware\IdentifyTenant;

$parentDomain = IdentifyTenant::getParentDomain();

// =========================================================================
// 1. TENANT / SUBDOMAIN ROUTES ({subdomain}.ritiksaini.in or {subdomain}.localhost)
// =========================================================================
Route::domain('{subdomain}.' . $parentDomain)->middleware(['identify.tenant'])->group(function () {

    // A. Public Blog Frontend (Shows only this subdomain's blogs)
    Route::get('/', [PublicBlogController::class, 'index'])->name('tenant.public.home');
    Route::get('/blog/{slug}', [PublicBlogController::class, 'show'])->name('tenant.public.single');

    // B. Tenant Admin Login (http://{subdomain}.ritiksaini.in/login)
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
// 2. CENTRAL / SUPERADMIN ROUTES (Direct Main Domain: multidomain.ritiksaini.in, localhost, 127.0.0.1)
// =========================================================================
Route::get('/', [SuperAdminController::class, 'showLoginForm'])->name('central.home');
Route::get('/login', [SuperAdminController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SuperAdminController::class, 'login']);
Route::post('/logout', [SuperAdminController::class, 'logout'])->name('superadmin.logout');

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/admins/create', [SuperAdminController::class, 'createAdmin'])->name('superadmin.admins.create');
    Route::post('/admins', [SuperAdminController::class, 'storeAdmin'])->name('superadmin.admins.store');
    Route::get('/admins/{id}/edit', [SuperAdminController::class, 'editAdmin'])->name('superadmin.admins.edit');
    Route::put('/admins/{id}', [SuperAdminController::class, 'updateAdmin'])->name('superadmin.admins.update');
    Route::patch('/admins/{id}/toggle-status', [SuperAdminController::class, 'toggleAdminStatus'])->name('superadmin.admins.toggle-status');
    Route::delete('/admins/{id}', [SuperAdminController::class, 'destroyAdmin'])->name('superadmin.admins.destroy');
});
