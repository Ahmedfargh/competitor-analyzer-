<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | Define the locales that the application supports.
    |
    */
    'supportedLocales' => [
        'en' => [
            'name' => 'English',
            'script' => 'Latn',
            'native' => 'English',
            'regional' => 'en_GB',
            'dir' => 'ltr',
        ],
        'ar' => [
            'name' => 'Arabic',
            'script' => 'Arab',
            'native' => 'العربية',
            'regional' => 'ar_AE',
            'dir' => 'rtl',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Accept Language Header
    |--------------------------------------------------------------------------
    |
    | Automatically determine locale from browser header on first visit.
    |
    */
    'useAcceptLanguageHeader' => true,

    /*
    |--------------------------------------------------------------------------
    | Hide Default Locale in URL
    |--------------------------------------------------------------------------
    |
    | If false, default locale will be included in the URL (e.g. /en/about).
    |
    */
    'hideDefaultLocaleInURL' => false,

    /*
    |--------------------------------------------------------------------------
    | Locales Display Order
    |--------------------------------------------------------------------------
    */
    'localesOrder' => ['en', 'ar'],

    'localesMapping' => [],

    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),

    'urlsIgnored' => ['/skipped', '/api/*', '/up'],

    'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],
];
