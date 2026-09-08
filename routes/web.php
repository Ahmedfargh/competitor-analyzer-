<?php

use App\Http\Controllers\CompetitorAnalysisController;
use App\Http\Controllers\PublicPostController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

foreach (config('tenancy.central_domains', ['localhost', '127.0.0.1']) as $domain) {
    Route::domain($domain)->group(function () {
        Route::post('/api/competitor-analysis', [CompetitorAnalysisController::class, 'analyze'])->name('competitor.analyze');

        Route::group([
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => [
                'localeSessionRedirect',
                'localizationRedirect',
                'localeViewPath',
            ],
        ], function () {
            Route::view('/', 'welcome')->name('home');
            Route::view('/about', 'pages.about')->name('about');
            Route::view('/features', 'pages.features')->name('features');
            Route::view('/pricing', 'pages.pricing')->name('pricing');
            Route::view('/contact', 'pages.contact')->name('contact');

            // Landing Pages & Blog Posts
            Route::get('/p/{slug}', [PublicPostController::class, 'show'])->name('posts.show');
        });
        Route::get('/p/{slug}', [PublicPostController::class, 'show']);
    });
}
