<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Tenant\Http\Controllers\TenantAuthController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Locale Switcher
    Route::get('/locale/{locale}', [TenantAuthController::class, 'switchLocale'])->name('tenant.locale');

    // Tenant Guest Authentication
    Route::get('/login', [TenantAuthController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [TenantAuthController::class, 'login'])->name('tenant.login.submit');
    Route::post('/logout', [TenantAuthController::class, 'logout'])->name('tenant.logout');

    // Tenant Authenticated Protected Area
    Route::middleware('auth:tenant')->group(function () {
        Route::get('/', [TenantAuthController::class, 'dashboard'])->name('tenant.dashboard');
    });
});
