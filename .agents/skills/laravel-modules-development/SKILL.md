---
name: laravel-modules-development
description: "Laravel Modules (nwidart/laravel-modules v13) modular monolith development. Use when creating, organizing, or refactoring modules, configuring module service providers and routes, managing inter-module communication, setting up module assets with Vite (vite-module-loader.js), writing module migrations/factories, or testing modular features."
license: MIT
metadata:
  author: laravel
---

# Laravel Modules Development

This application organizes domain boundaries using `nwidart/laravel-modules` v13.

## Directory Structure

Modules live in the `Modules/` root directory:

```
Modules/
├── Admin/
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   ├── Models/
│   │   └── Providers/
│   │       ├── AdminServiceProvider.php
│   │       └── RouteServiceProvider.php
│   ├── config/
│   ├── database/
│   │   ├── factories/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── resources/
│   │   ├── assets/
│   │   │   ├── js/
│   │   │   └── sass/ or css/
│   │   └── views/
│   ├── routes/
│   │   ├── api.php
│   │   └── web.php
│   ├── tests/
│   ├── module.json
│   └── composer.json
```

Module activation status is tracked in `modules_statuses.json`.

---

## Inter-Module Boundaries & Rules

1. **Loose Coupling**: Modules should encapsulate their own business logic. Avoid direct tight coupling between modules (e.g., Module A controller querying Module B internal Eloquent models directly).
2. **Communication Mechanisms**:
   - **Domain Events**: Dispatch events (`Event::dispatch(new UserRegistered($user))`) and let other modules listen asynchronously.
   - **Contracts / Service Interfaces**: Expose public interfaces/DTOs in a shared contract layer or root `app/Contracts/` if multiple modules need access.
3. **Module Independence**: A module should be capable of being enabled or disabled without breaking core boot cycles (use service providers and conditional binding where appropriate).

---

## Routing & Views

- **Routes**: Loaded via the module's `RouteServiceProvider`. Web routes are typically prefixed or domain-isolated.
- **Views**: Registered with namespaced views:
  ```php
  // In Module ServiceProvider:
  $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');
  ```
  Render in controllers:
  ```php
  return view('admin::dashboard.index', compact('stats'));
  ```
- **Blade Components**: Use module component prefix:
  ```blade
  <x-admin::header title="Dashboard" />
  ```

---

## Assets & Vite Integration

This project uses `vite-module-loader.js` to discover and compile module assets dynamically.

- Module CSS/JS files should be placed in `Modules/<ModuleName>/resources/assets/`.
- In Blade templates, include module assets via Vite:
  ```blade
  {{-- Core Vite assets --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Module Vite assets --}}
  @vite(['Modules/Admin/resources/assets/sass/app.scss', 'Modules/Admin/resources/assets/js/app.js'])
  ```

---

## Artisan CLI Commands

Always use Artisan module commands:

```bash
# Create a new module
php artisan module:make Blog

# Make components inside a module
php artisan module:make-controller PostController Blog
php artisan module:make-model Post Blog -m
php artisan module:make-migration create_posts_table Blog
php artisan module:make-request StorePostRequest Blog

# Migrations & Status
php artisan module:migrate Blog
php artisan module:migrate-rollback Blog
php artisan module:seed Blog
php artisan module:enable Blog
php artisan module:disable Blog
php artisan module:list
```

---

## Testing Modules

Module-specific tests reside in `Modules/<ModuleName>/tests/Feature` and `Modules/<ModuleName>/tests/Unit`.

Run tests scoped to a module:
```bash
php artisan test Modules/Admin/tests
vendor/bin/phpunit Modules/Admin/tests
```
