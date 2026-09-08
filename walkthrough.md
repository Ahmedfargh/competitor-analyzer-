# Contract-Driven Architecture & Admin Command Hub Walkthrough

## Summary of Completed Implementation

We have implemented an **Interface-First Contract-Driven Architecture** across all layers (`FormRequest`, `DTO`, `Repository`, `Service`) enabling dynamic per-tenant customization, along with an **Admin Command Hub** featuring:
- **Subscription Plans in Egyptian Pounds (EGP)** (`1,490 ج.م`, `3,490 ج.م`, `9,990 ج.م`) and USD with currency & billing frequency toggles.
- **Stancl Multi-Tenant CRUD & Isolated Data Browser** allowing administrators to safely inspect tenant schemas, table lists, and registered users.
- **Activity & Security Audit Log** recording administrative actions, Gemini missions, and tenant provisioning.
- **Dark Luxury Aesthetic** (`#09090b` background, glowing `#f97316` orange & amber accents, Cairo font for Arabic RTL and Plus Jakarta Sans for English).

---

## 1. Architectural Contract & Layer Mapping

Every layer is governed by an explicit PHP interface, decoupled from concrete Eloquent or HTTP classes, and bound through Laravel's Service Container with dynamic per-tenant resolution support:

```
┌─────────────────────────────┐       ┌─────────────────────────────┐
│  StoreTenantRequestInterface │ ───>  │  CreateTenantDTOInterface   │
└─────────────────────────────┘       └─────────────────────────────┘
               │                                     │
               ▼                                     ▼
┌─────────────────────────────┐       ┌─────────────────────────────┐
│   TenantServiceInterface    │ ───>  │  TenantRepositoryInterface  │
└─────────────────────────────┘       └─────────────────────────────┘
```

### Layer Interfaces & Concrete Implementations

| Layer | Contract Interface | Concrete Implementation | Tenant Customization Support |
|---|---|---|---|
| **Form Request** | [`StoreTenantRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/StoreTenantRequestInterface.php)<br>[`UpdateTenantRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/UpdateTenantRequestInterface.php)<br>[`StorePlanRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/StorePlanRequestInterface.php)<br>[`AdminLoginRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/AdminLoginRequestInterface.php) | [`StoreTenantRequest`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Tenant/StoreTenantRequest.php)<br>[`UpdateTenantRequest`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Tenant/UpdateTenantRequest.php)<br>[`StorePlanRequest`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Plan/StorePlanRequest.php)<br>[`AdminLoginRequest`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Auth/AdminLoginRequest.php) | Resolvable via container; allows tenant-specific validation rules per tenant slug or subdomain |
| **DTO** | [`CreateTenantDTOInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Contracts/CreateTenantDTOInterface.php)<br>[`UpdateTenantDTOInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Contracts/UpdateTenantDTOInterface.php)<br>[`SubscriptionPlanDTOInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Contracts/SubscriptionPlanDTOInterface.php)<br>[`ActivityLogDTOInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Contracts/ActivityLogDTOInterface.php) | [`CreateTenantDTO`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Tenant/CreateTenantDTO.php)<br>[`UpdateTenantDTO`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Tenant/UpdateTenantDTO.php)<br>[`SubscriptionPlanDTO`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Plan/SubscriptionPlanDTO.php)<br>[`ActivityLogDTO`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Activity/ActivityLogDTO.php) | Strongly-typed immutable data envelopes |
| **Repository** | [`TenantRepositoryInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Contracts/TenantRepositoryInterface.php)<br>[`SubscriptionPlanRepositoryInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Contracts/SubscriptionPlanRepositoryInterface.php)<br>[`ActivityLogRepositoryInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Contracts/ActivityLogRepositoryInterface.php) | [`EloquentTenantRepository`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Eloquent/EloquentTenantRepository.php)<br>[`EloquentSubscriptionPlanRepository`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Eloquent/EloquentSubscriptionPlanRepository.php)<br>[`EloquentActivityLogRepository`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Eloquent/EloquentActivityLogRepository.php) | Database abstraction layer; allows replacing Eloquent with alternative storage or tenant databases |
| **Service** | [`TenantServiceInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/Contracts/TenantServiceInterface.php)<br>[`SubscriptionPlanServiceInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/Contracts/SubscriptionPlanServiceInterface.php)<br>[`ActivityLogServiceInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/Contracts/ActivityLogServiceInterface.php) | [`TenantService`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/TenantService.php)<br>[`SubscriptionPlanService`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/SubscriptionPlanService.php)<br>[`ActivityLogService`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/ActivityLogService.php) | Business domain logic; coordinates transactions, audit logs, and Stancl `tenant()->run(...)` isolation |

---

## 2. Per-Tenant Layer Customization Mechanism

Implemented [`TenantLayerCustomizer`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Support/TenantLayerCustomizer.php) and container bindings in [`AdminServiceProvider`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Providers/AdminServiceProvider.php).

### How It Works:
```php
// Any tenant or enterprise add-on can override any layer for a specific tenant:
TenantLayerCustomizer::register('enterprise-tenant-1', TenantServiceInterface::class, CustomEnterpriseTenantService::class);

// The controller or service automatically resolves the tenant override:
$service = app(TenantServiceInterface::class); // returns CustomEnterpriseTenantService for enterprise-tenant-1
```

---

## 3. Subscription Plans in EGP

Created `subscription_plans` table and seeded plans with prices in **Egyptian Pounds (EGP)**:
1. **Starter / خطة البداية**: `1,490 ج.م` / month ($29 USD)
2. **Professional / خطة المحترفين**: `3,490 ج.م` / month ($69 USD)
3. **Enterprise / خطة المؤسسات**: `9,990 ج.م` / month ($199 USD)

Updated public pricing partial [`resources/views/partials/pricing-cards.blade.php`](file:///home/ahmed/laravel-tech/compitator-project/resources/views/partials/pricing-cards.blade.php) with an interactive **EGP (ج.م) / USD ($)** currency switcher and billing interval controls.

---

## 4. Multi-Tenant Data Browser & CRUD

Under `/admin/tenants`:
- **Tenant Management**: Provision partitions with ID, company name, domain, and subscription plan.
- **Tenant Data Browser (`/admin/tenants/{tenant}`)**: Uses Stancl Tenancy's `$tenant->run(fn () => ...)` context to safely query:
  - Database connectivity & migration state
  - Table schemas present inside the tenant's isolated database
  - User count and latest registered users in the tenant database
  - Assigned subscription tier & domain routing
- **Tenant Context Reversion & Asset Protection**: Enforced strict `finally { if (tenancy()->initialized) { tenancy()->end(); } }` in `browseTenantData` and migrated the tenant `cache` table so that inspecting tenant databases never leaves tenancy context active or corrupts central Vite/CSS asset URLs.

---

---

## 5. Livewire Auto-Discovery Architecture (No ServiceProvider Manual Registration)

To eliminate tedious, error-prone manual component registration in `AdminServiceProvider::boot()`, all Livewire components have been structured according to canonical Laravel Livewire auto-discovery standards:

- **Component Classes**: Located in `app/Livewire/Admin/...`
  - [`App\Livewire\Admin\DashboardOverview`](file:///home/ahmed/laravel-tech/compitator-project/app/Livewire/Admin/DashboardOverview.php) -> `<livewire:admin.dashboard-overview />`
  - [`App\Livewire\Admin\Tenants\TenantManager`](file:///home/ahmed/laravel-tech/compitator-project/app/Livewire/Admin/Tenants/TenantManager.php) -> `<livewire:admin.tenants.tenant-manager />`
  - [`App\Livewire\Admin\Tenants\TenantDataBrowser`](file:///home/ahmed/laravel-tech/compitator-project/app/Livewire/Admin/Tenants/TenantDataBrowser.php) -> `<livewire:admin.tenants.tenant-data-browser :tenant-id="$tenant->id" />`
  - [`App\Livewire\Admin\Plans\PlanManager`](file:///home/ahmed/laravel-tech/compitator-project/app/Livewire/Admin/Plans/PlanManager.php) -> `<livewire:admin.plans.plan-manager />`
  - [`App\Livewire\Admin\ActivityLogs\ActivityLogFeed`](file:///home/ahmed/laravel-tech/compitator-project/app/Livewire/Admin/ActivityLogs/ActivityLogFeed.php) -> `<livewire:admin.activity-logs.activity-log-feed />`
- **Component Views**: Located in `resources/views/livewire/admin/...`
- **Zero ServiceProvider Clutter**: `AdminServiceProvider::boot()` remains completely clean with zero manual `Livewire::component(...)` calls. Livewire's built-in `Finder` resolves all components dynamically from `App\Livewire`.

---

## 6. System Admin Users, Roles & Permissions Management (RBAC)

Implemented a comprehensive Access Control & Administration system following the strict **Interface-First Architecture**:

### 1. Architectural Layers & Contracts
- **DTOs**: [`AdminUserDTOInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Contracts/AdminUserDTOInterface.php), [`RoleDTOInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Contracts/RoleDTOInterface.php), [`PermissionDTOInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Contracts/PermissionDTOInterface.php).
- **Form Requests**: [`StoreAdminUserRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/StoreAdminUserRequestInterface.php), [`UpdateAdminUserRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/UpdateAdminUserRequestInterface.php), [`StoreRoleRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/StoreRoleRequestInterface.php), [`UpdateRoleRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/UpdateRoleRequestInterface.php), [`StorePermissionRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/StorePermissionRequestInterface.php).
- **Repositories**: [`AdminUserRepositoryInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Contracts/AdminUserRepositoryInterface.php), [`RoleRepositoryInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Contracts/RoleRepositoryInterface.php), [`PermissionRepositoryInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Contracts/PermissionRepositoryInterface.php) with Eloquent implementations.
- **Services**: [`AdminUserServiceInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/Contracts/AdminUserServiceInterface.php), [`RoleServiceInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/Contracts/RoleServiceInterface.php), [`PermissionServiceInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/Contracts/PermissionServiceInterface.php) with automatic audit logging on every administrative event (`admin_user.created`, `role.updated`, etc.).
- **Container Bindings**: Bound via `TenantLayerCustomizer::resolve(...)` in [`AdminServiceProvider`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Providers/AdminServiceProvider.php).

### 2. Reactive Livewire Management
- **System Admins Manager** ([`AdminUserManager`](file:///home/ahmed/laravel-tech/compitator-project/app/Livewire/Admin/Users/AdminUserManager.php)):
  - Live filtering by search query and role filter.
  - Inline create & edit modal with role assignment checkboxes and password reset.
  - Self-deletion guard preventing logged-in administrators from deleting their own accounts.
- **Roles Manager** ([`RoleManager`](file:///home/ahmed/laravel-tech/compitator-project/app/Livewire/Admin/Roles/RoleManager.php)):
  - Role management displaying active member counts and assigned permissions.
  - Multi-selection permission matrix grouped by domain module (`tenants`, `plans`, `users`, `roles`, `logs`).
  - System protection guard preventing deletion of the `super_admin` role.
- **Permissions Manager** ([`PermissionManager`](file:///home/ahmed/laravel-tech/compitator-project/app/Livewire/Admin/Permissions/PermissionManager.php)):
  - Group-filtered permission chips with instant deletion and quick creation modals.

### 3. Sidebar & Localization
- Added glowing sidebar navigation under **Access Control** (`/admin/users`, `/admin/roles`, `/admin/permissions`).
- Full bilingual localization in English ([`lang/en/admin.php`](file:///home/ahmed/laravel-tech/compitator-project/lang/en/admin.php)) and Arabic RTL ([`lang/ar/admin.php`](file:///home/ahmed/laravel-tech/compitator-project/lang/ar/admin.php)).
- Seeded default roles (`super_admin`, `support`, `billing_manager`) via [`AdminRbacSeeder`](file:///home/ahmed/laravel-tech/compitator-project/database/seeders/AdminRbacSeeder.php).

---

---

## 7. Dynamic Tenant Feature Customization (Database-Driven Class Resolution)

Implemented database-backed tenant feature customization enabling tenant-specific class resolution:

### 1. Database Schema & Storage
- Migration [`2026_09_08_190000_create_tenant_feature_customizations_table.php`](file:///home/ahmed/laravel-tech/compitator-project/database/migrations/2026_09_08_190000_create_tenant_feature_customizations_table.php):
  - `tenant_id`: Foreign key to `tenants.id` on delete cascade.
  - `base_class`: The interface or core implementation class name.
  - `alternative_class`: The customized class namespace/FQCN for that specific tenant.
  - `is_active`: Boolean flag allowing toggling customization on or off.
  - Unique index on `['tenant_id', 'base_class']`.

### 2. Models, Relations & Observers
- Model [`TenantFeatureCustomization`](file:///home/ahmed/laravel-tech/compitator-project/app/Models/TenantFeatureCustomization.php):
  - Conforms to `protected $guarded = ['id'];`.
  - Observed by [`TenantFeatureCustomizationObserver`](file:///home/ahmed/laravel-tech/compitator-project/app/Observers/TenantFeatureCustomizationObserver.php) in the same directory/module (`app/Observers/`), logging actions (`tenant.feature_customized`, `tenant.feature_updated`, `tenant.feature_removed`).
- Model [`Tenant`](file:///home/ahmed/laravel-tech/compitator-project/app/Models/Tenant.php):
  - HasMany relationship `featureCustomizations()`.
  - Helper method `resolveFeatureClass(string $baseClass, ?string $defaultImplementation = null)`.

### 3. Namespace-Based Dynamic Resolution
- Resolver [`TenantLayerCustomizer`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Support/TenantLayerCustomizer.php):
  - `resolveCurrentTenantId(?string $tenantId)`: Detects tenant from parameter, Stancl Tenancy context (`tenant('id')`, `tenancy()->tenant`), or HTTP route context.
  - `resolveClass(string $baseClass, ?string $defaultImplementation = null, ?string $tenantId = null)`: Looks up active database customization for current tenant; if found and alternative class exists, returns alternative namespace string.
  - `resolve(...)`: Instantiates the resolved alternative class via `app()->make($alternativeClass)`.
  - Automatic fallback to default implementation / base class if no customization exists or if deactivated.

### 4. High-Performance Caching Layer
- Integrated `Illuminate\Support\Facades\Cache` in [`TenantLayerCustomizer`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Support/TenantLayerCustomizer.php):
  - `getCustomizationsForTenant(string $tenantId)`: Caches all active `[base_class => alternative_class]` mappings in cache key `"tenant_customizations:{$tenantId}"` with a 24-hour TTL (`public static int $cacheTtl = 86400`).
  - Single cache hit resolves all customized classes across services, repositories, form requests, and DTOs with zero repeated DB queries.
  - Automatic Cache Invalidation: [`TenantFeatureCustomizationObserver`](file:///home/ahmed/laravel-tech/compitator-project/app/Observers/TenantFeatureCustomizationObserver.php) triggers `TenantLayerCustomizer::clearTenantCache($tenantId)` on `created`, `updated`, and `deleted` model events, ensuring immediate consistency.

### 5. Dynamic Seeder for Customization Layer
- Dynamic Seeder [`TenantFeatureCustomizationSeeder`](file:///home/ahmed/laravel-tech/compitator-project/database/seeders/TenantFeatureCustomizationSeeder.php):
  - `seedForTenant(string $tenantId, array $mappings, bool $defaultActive = true)`: Dynamically upserts base-to-alternative class bindings for any tenant, clearing tenant cache automatically.
  - `registerMapping(string $baseClass, string $alternativeClass)`: Allows runtime registration of default customization blueprints.
  - `run(?string $tenantId = null, array $customMappings = [])`: Automatically seeds active tenants or auto-provisions an enterprise demo tenant (`enterprise-demo` / `enterprise.localhost`) if no tenants exist.
  - Sample customized service: [`CustomTenantActivityLogService`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Tenant/app/Services/CustomTenantActivityLogService.php) enriching tenant audit logs.
  - Wired into [`DatabaseSeeder`](file:///home/ahmed/laravel-tech/compitator-project/database/seeders/DatabaseSeeder.php).

---

## 9. WordPress-Like Gutenberg Block Editor for Landing Pages & Posts

Implemented a comprehensive visual block editor for designing high-conversion landing pages and rich blog articles:

### 1. Database & Model Architecture
- **Migration** [`2026_09_08_200000_create_posts_table.php`](file:///home/ahmed/laravel-tech/compitator-project/database/migrations/2026_09_08_200000_create_posts_table.php):
  - `title`, `excerpt`: JSON translatable (Spatie Translatable for English and Arabic).
  - `slug`: Unique per tenant/system, indexed.
  - `type`: `landing_page` or `post`.
  - `status`: `draft` or `published`.
  - `blocks`: JSON array storing the complete block tree.
  - `seo_meta`: JSON storing meta title, description, and social graph data.
  - `author_id`, `tenant_id`, `published_at`, timestamps.
- **Model** [`Post`](file:///home/ahmed/laravel-tech/compitator-project/app/Models/Post.php):
  - Strictly follows `protected $guarded = ['id'];`.
  - Observed by [`PostObserver`](file:///home/ahmed/laravel-tech/compitator-project/app/Observers/PostObserver.php) in `app/Observers/` logging activity audits (`post.created`, `post.published`, `post.deleted`).

### 2. Interface-First Architecture
- **Contracts & Implementations**:
  - DTO: [`PostDTOInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Contracts/PostDTOInterface.php) & [`PostDTO`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/DTOs/Post/PostDTO.php).
  - Form Requests: [`StorePostRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/StorePostRequestInterface.php) & [`UpdatePostRequestInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Http/Requests/Contracts/UpdatePostRequestInterface.php).
  - Repository: [`PostRepositoryInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Contracts/PostRepositoryInterface.php) & [`EloquentPostRepository`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Repositories/Eloquent/EloquentPostRepository.php).
  - Service: [`PostServiceInterface`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/Contracts/PostServiceInterface.php) & [`PostService`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Services/PostService.php).
  - Dynamic Container Bindings: Wired via `TenantLayerCustomizer::resolve(...)` in [`AdminServiceProvider`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Providers/AdminServiceProvider.php).

### 3. Gutenberg-Style Visual Block Editor
- **Livewire Components**:
  - [`PostManager`](file:///home/ahmed/laravel-tech/compitator-project/app/Livewire/Admin/Posts/PostManager.php): Post dashboard with real-time search, type filtering (`landing_page` vs `post`), status toggling, quick duplication, and deletion.
  - [`PostBlockEditor`](file:///home/ahmed/laravel-tech/compitator-project/app/Livewire/Admin/Posts/PostBlockEditor.php): Dual-mode WordPress-style visual canvas and inspector:
    - **Dual Mode**: Instant toggle between Visual Block Editor and Real-Time Live Preview.
    - **Block Manipulations**: Move Up/Down, Duplicate, Delete, Insert Block at index.
    - **Document Inspector**: Slug generation, type, status, featured image, excerpt, and SEO meta tags.
    - **Bilingual Tabs**: Switch between English (`EN`) and Arabic (`AR`) content editing.
- **9 Specialized Block Components** ([`resources/views/components/post-blocks/`](file:///home/ahmed/laravel-tech/compitator-project/resources/views/components/post-blocks/)):
  1. `hero`: Landing page hero with glowing gradients, badges, headline, subtitle, and primary/secondary CTA buttons.
  2. `heading`: H1, H2, H3, H4 section titles with alignment controls.
  3. `paragraph`: Rich body content with line breaks and dropcap.
  4. `image`: Visual asset with caption, alt text, and aspect ratio (`16:9`, `4:3`, `1:1`, `full`).
  5. `features_grid`: Multi-column card grid (2, 3, or 4 columns) with icons, titles, and descriptions.
  6. `cta_banner`: High-conversion lead generation banner with button links.
  7. `faq`: Interactive collapsible accordion for questions and answers.
  8. `quote`: Testimonial quote with author and citation.
  9. `code`: Syntax formatted terminal snippet with language badge.
- **Public Rendering**:
  - Dedicated public page [`resources/views/posts/show.blade.php`](file:///home/ahmed/laravel-tech/compitator-project/resources/views/posts/show.blade.php) accessible via `/p/{slug}`.

---

## 10. Verification & Test Results

All 74 feature tests pass with 100% success rate:

```bash
php artisan test --compact
```

Output:
```
Tests:    74 passed (275 assertions)
Duration: 17.34s
```





