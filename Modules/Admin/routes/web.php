<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Admin\Http\Controllers\AdminActivityLogController;
use Modules\Admin\Http\Controllers\AdminAuthController;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\AdminDashboardController;
use Modules\Admin\Http\Controllers\AdminPlanController;
use Modules\Admin\Http\Controllers\AdminTenantController;
use Modules\Admin\Http\Controllers\AdminUserController;
use Modules\Admin\Http\Controllers\PermissionController;
use Modules\Admin\Http\Controllers\PostController;
use Modules\Admin\Http\Controllers\RoleController;

Route::middleware(['auth:admin'])->group(function () {
    Route::resource('admins', AdminController::class)->names('admin');
});

foreach (config('tenancy.central_domains', ['localhost', '127.0.0.1']) as $domain) {
    Route::domain($domain)->group(function () {
        Route::group([
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => [
                'localeSessionRedirect',
                'localizationRedirect',
                'localeViewPath',
            ],
        ], function () {
            // Guest Admin Routes
            Route::prefix('admin')->name('admin.')->group(function () {
                Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
                Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');

                // Authenticated Admin Routes
                Route::middleware('auth:admin')->group(function () {
                    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

                    // Dashboard Overview
                    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

                    // Tenants Management & Data Browser
                    Route::resource('tenants', AdminTenantController::class);

                    // Subscription Plans Management (EGP Pricing)
                    Route::resource('plans', AdminPlanController::class)->except(['show']);

                    // Landing Pages & Blog Posts (WordPress-like Editor)
                    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
                    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
                    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

                    // Activity & Audit Logs
                    Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');

                    // System Admin Users, Roles & Permissions Management
                    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
                    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
                    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
                });
            });
        });
    });
}
