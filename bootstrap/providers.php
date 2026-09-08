<?php

use App\Providers\AppServiceProvider;
use App\Providers\TenancyServiceProvider;
use Nwidart\Modules\LaravelModulesServiceProvider;

return [
    AppServiceProvider::class,
    LaravelModulesServiceProvider::class,
    TenancyServiceProvider::class,
];
