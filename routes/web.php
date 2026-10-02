<?php

use App\Http\Controllers\CompetitorAnalysisController;
use App\Http\Controllers\PublicPostController;
use Illuminate\Support\Facades\Route;

Route::post('/api/competitor-analysis', [CompetitorAnalysisController::class, 'analyze'])->name('competitor.analyze');

$supportedLocales = array_keys(config('laravellocalization.supportedLocales', ['en' => [], 'ar' => []]));

// Fallback & Redirect for un-prefixed root and routes
Route::group([
    'middleware' => [
        'localeSessionRedirect',
        'localizationRedirect',
        'localeViewPath',
    ],
], function () {
    Route::get('/', function () {
        $locale = session('locale', config('app.locale', 'en'));

        return redirect('/'.$locale);
    });
    Route::view('/about', 'pages.about');
    Route::view('/features', 'pages.features');
    Route::view('/pricing', 'pages.pricing');
    Route::view('/contact', 'pages.contact');
    Route::get('/p/{slug}', [PublicPostController::class, 'show']);
});

// Localized routes for all supported locales (Octane/FrankenPHP compatible)
foreach ($supportedLocales as $locale) {
    Route::group([
        'prefix' => $locale,
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
}
