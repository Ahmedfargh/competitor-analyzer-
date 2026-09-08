<?php

namespace Modules\Admin\Providers;

use Modules\Admin\Http\Requests\Access\StoreAdminUserRequest;
use Modules\Admin\Http\Requests\Access\StorePermissionRequest;
use Modules\Admin\Http\Requests\Access\StoreRoleRequest;
use Modules\Admin\Http\Requests\Access\UpdateAdminUserRequest;
use Modules\Admin\Http\Requests\Access\UpdateRoleRequest;
use Modules\Admin\Http\Requests\Auth\AdminLoginRequest;
use Modules\Admin\Http\Requests\Contracts\AdminLoginRequestInterface;
use Modules\Admin\Http\Requests\Contracts\StoreAdminUserRequestInterface;
use Modules\Admin\Http\Requests\Contracts\StorePermissionRequestInterface;
use Modules\Admin\Http\Requests\Contracts\StorePlanRequestInterface;
use Modules\Admin\Http\Requests\Contracts\StorePostRequestInterface;
use Modules\Admin\Http\Requests\Contracts\StoreRoleRequestInterface;
use Modules\Admin\Http\Requests\Contracts\StoreTenantRequestInterface;
use Modules\Admin\Http\Requests\Contracts\UpdateAdminUserRequestInterface;
use Modules\Admin\Http\Requests\Contracts\UpdatePostRequestInterface;
use Modules\Admin\Http\Requests\Contracts\UpdateRoleRequestInterface;
use Modules\Admin\Http\Requests\Contracts\UpdateTenantRequestInterface;
use Modules\Admin\Http\Requests\Plan\StorePlanRequest;
use Modules\Admin\Http\Requests\Post\StorePostRequest;
use Modules\Admin\Http\Requests\Post\UpdatePostRequest;
use Modules\Admin\Http\Requests\Tenant\StoreTenantRequest;
use Modules\Admin\Http\Requests\Tenant\UpdateTenantRequest;
use Modules\Admin\Repositories\Contracts\ActivityLogRepositoryInterface;
use Modules\Admin\Repositories\Contracts\AdminUserRepositoryInterface;
use Modules\Admin\Repositories\Contracts\PermissionRepositoryInterface;
use Modules\Admin\Repositories\Contracts\PostRepositoryInterface;
use Modules\Admin\Repositories\Contracts\RoleRepositoryInterface;
use Modules\Admin\Repositories\Contracts\SubscriptionPlanRepositoryInterface;
use Modules\Admin\Repositories\Contracts\TenantRepositoryInterface;
use Modules\Admin\Repositories\Eloquent\EloquentActivityLogRepository;
use Modules\Admin\Repositories\Eloquent\EloquentAdminUserRepository;
use Modules\Admin\Repositories\Eloquent\EloquentPermissionRepository;
use Modules\Admin\Repositories\Eloquent\EloquentPostRepository;
use Modules\Admin\Repositories\Eloquent\EloquentRoleRepository;
use Modules\Admin\Repositories\Eloquent\EloquentSubscriptionPlanRepository;
use Modules\Admin\Repositories\Eloquent\EloquentTenantRepository;
use Modules\Admin\Services\ActivityLogService;
use Modules\Admin\Services\AdminUserService;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Services\Contracts\AdminUserServiceInterface;
use Modules\Admin\Services\Contracts\PermissionServiceInterface;
use Modules\Admin\Services\Contracts\PostServiceInterface;
use Modules\Admin\Services\Contracts\RoleServiceInterface;
use Modules\Admin\Services\Contracts\SubscriptionPlanServiceInterface;
use Modules\Admin\Services\Contracts\TenantServiceInterface;
use Modules\Admin\Services\PermissionService;
use Modules\Admin\Services\PostService;
use Modules\Admin\Services\RoleService;
use Modules\Admin\Services\SubscriptionPlanService;
use Modules\Admin\Services\TenantService;
use Modules\Admin\Support\TenantLayerCustomizer;
use Nwidart\Modules\Support\ModuleServiceProvider;

class AdminServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Admin';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'admin';

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        parent::register();

        // Bind Repositories to Interfaces with tenant-level customization support
        $this->app->bind(
            TenantRepositoryInterface::class,
            fn () => TenantLayerCustomizer::resolve(TenantRepositoryInterface::class, EloquentTenantRepository::class)
        );
        $this->app->bind(
            SubscriptionPlanRepositoryInterface::class,
            fn () => TenantLayerCustomizer::resolve(SubscriptionPlanRepositoryInterface::class, EloquentSubscriptionPlanRepository::class)
        );
        $this->app->bind(
            ActivityLogRepositoryInterface::class,
            fn () => TenantLayerCustomizer::resolve(ActivityLogRepositoryInterface::class, EloquentActivityLogRepository::class)
        );
        $this->app->bind(
            AdminUserRepositoryInterface::class,
            fn () => TenantLayerCustomizer::resolve(AdminUserRepositoryInterface::class, EloquentAdminUserRepository::class)
        );
        $this->app->bind(
            RoleRepositoryInterface::class,
            fn () => TenantLayerCustomizer::resolve(RoleRepositoryInterface::class, EloquentRoleRepository::class)
        );
        $this->app->bind(
            PermissionRepositoryInterface::class,
            fn () => TenantLayerCustomizer::resolve(PermissionRepositoryInterface::class, EloquentPermissionRepository::class)
        );

        // Bind Services to Interfaces with tenant-level customization support
        $this->app->bind(
            TenantServiceInterface::class,
            fn () => TenantLayerCustomizer::resolve(TenantServiceInterface::class, TenantService::class)
        );
        $this->app->bind(
            SubscriptionPlanServiceInterface::class,
            fn () => TenantLayerCustomizer::resolve(SubscriptionPlanServiceInterface::class, SubscriptionPlanService::class)
        );
        $this->app->bind(
            ActivityLogServiceInterface::class,
            fn () => TenantLayerCustomizer::resolve(ActivityLogServiceInterface::class, ActivityLogService::class)
        );
        $this->app->bind(
            AdminUserServiceInterface::class,
            fn () => TenantLayerCustomizer::resolve(AdminUserServiceInterface::class, AdminUserService::class)
        );
        $this->app->bind(
            RoleServiceInterface::class,
            fn () => TenantLayerCustomizer::resolve(RoleServiceInterface::class, RoleService::class)
        );
        $this->app->bind(
            PermissionServiceInterface::class,
            fn () => TenantLayerCustomizer::resolve(PermissionServiceInterface::class, PermissionService::class)
        );

        // Bind Form Requests to Interfaces for automatic validation & tenant customizability
        $this->app->bind(
            StoreTenantRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(StoreTenantRequestInterface::class, StoreTenantRequest::class)
        );
        $this->app->bind(
            UpdateTenantRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(UpdateTenantRequestInterface::class, UpdateTenantRequest::class)
        );
        $this->app->bind(
            StorePlanRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(StorePlanRequestInterface::class, StorePlanRequest::class)
        );
        $this->app->bind(
            AdminLoginRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(AdminLoginRequestInterface::class, AdminLoginRequest::class)
        );
        $this->app->bind(
            StoreAdminUserRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(StoreAdminUserRequestInterface::class, StoreAdminUserRequest::class)
        );
        $this->app->bind(
            UpdateAdminUserRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(UpdateAdminUserRequestInterface::class, UpdateAdminUserRequest::class)
        );
        $this->app->bind(
            StoreRoleRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(StoreRoleRequestInterface::class, StoreRoleRequest::class)
        );
        $this->app->bind(
            UpdateRoleRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(UpdateRoleRequestInterface::class, UpdateRoleRequest::class)
        );
        $this->app->bind(
            PostRepositoryInterface::class,
            fn () => TenantLayerCustomizer::resolve(PostRepositoryInterface::class, EloquentPostRepository::class)
        );

        $this->app->bind(
            PostServiceInterface::class,
            fn () => TenantLayerCustomizer::resolve(PostServiceInterface::class, PostService::class)
        );

        $this->app->bind(
            StorePostRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(StorePostRequestInterface::class, StorePostRequest::class)
        );
        $this->app->bind(
            UpdatePostRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(UpdatePostRequestInterface::class, UpdatePostRequest::class)
        );
        $this->app->bind(
            StorePermissionRequestInterface::class,
            fn () => TenantLayerCustomizer::resolve(StorePermissionRequestInterface::class, StorePermissionRequest::class)
        );
    }

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
