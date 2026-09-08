<?php

namespace Modules\Admin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Admin\Models\Admin;
use Modules\Admin\Observers\AdminObserver;
use Modules\Admin\Observers\PermissionObserver;
use Modules\Admin\Observers\RoleObserver;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [];

    /**
     * The model observers for the module.
     *
     * @var array<string, string|array<int, string>>
     */
    protected $observers = [
        Admin::class => [AdminObserver::class],
        Role::class => [RoleObserver::class],
        Permission::class => [PermissionObserver::class],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
