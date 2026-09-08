---
name: blade-component-architecture
description: "Laravel Blade component design, architectural patterns, and reusable UI construction. Use when creating anonymous or class-based components, composing complex layouts, managing slots and scoped slots, using attribute bags ($attributes->class, $attributes->merge), passing contextual data with @aware, decoupling presentation with ViewModels, or organizing modular view components."
license: MIT
metadata:
  author: laravel
---

# Blade Component Architecture

This skill governs the structure, reusability, and encapsulation of Laravel Blade components.

## Component Types & Selection

### 1. Anonymous Components (Preferred for UI Primitives)
Place in `resources/views/components/` or `Modules/<ModuleName>/resources/views/components/`.
Best for buttons, badges, inputs, cards, modals, and presentational elements.

```blade
{{-- resources/views/components/badge.blade.php --}}
@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
$baseClasses = 'inline-flex items-center font-medium rounded-full transition-colors';

$variantClasses = match ($variant) {
    'primary' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300',
    'success' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
    'danger'  => 'bg-rose-50 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300',
    default   => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
};

$sizeClasses = match ($size) {
    'sm' => 'px-2 py-0.5 text-xs',
    'lg' => 'px-3 py-1.5 text-sm',
    default => 'px-2.5 py-1 text-xs',
};
@endphp

<span {{ $attributes->class([$baseClasses, $variantClasses, $sizeClasses]) }}>
    {{ $slot }}
</span>
```

### 2. Class-Based Components
Place in `app/View/Components/` or `Modules/<ModuleName>/app/View/Components/`.
Use when rendering requires dependency injection, complex data aggregation, or heavy conditional logic before rendering.

---

## Slots & Composition

Use named slots to create flexible layouts and containers:

```blade
{{-- resources/views/components/card.blade.php --}}
@props(['title' => null])

<div {{ $attributes->class(['rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm']) }}>
    @if ($title || isset($header))
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            @if (isset($header))
                {{ $header }}
            @else
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
            @endif

            @if (isset($actions))
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if (isset($footer))
        <div class="px-6 py-3 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 rounded-b-xl">
            {{ $footer }}
        </div>
    @endif
</div>
```

---

## Attribute Bags & Class Merging

1. **`$attributes->class(...)`**: Always use `$attributes->class([])` to combine default style classes with consumer-provided classes.
2. **`$attributes->merge(...)`**: Use for merging default HTML attributes such as `type="text"`, `rows="4"`, etc.
3. **Escaping & Clean Attributes**: Ensure consumer attributes are properly echoed (`{{ $attributes }}`) so attributes like `id`, `name`, `data-*`, and event listeners pass through smoothly.

---

## Context Sharing with `@aware`

When building compound components (like `<x-form>` containing `<x-input-group>` and `<x-input>`), share state without prop drilling:

```blade
{{-- resources/views/components/form/group.blade.php --}}
@props(['name', 'label' => null])

<div class="space-y-1">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            {{ $label }}
        </label>
    @endif

    {{ $slot }}

    @error($name)
        <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- resources/views/components/form/input.blade.php --}}
@aware(['name'])
@props(['type' => 'text'])

<input
    type="{{ $type }}"
    id="{{ $name }}"
    name="{{ $name }}"
    {{ $attributes->class(['w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-sm focus:ring-indigo-500 focus:border-indigo-500']) }}
/>
```

---

## ViewModels for Presentation Decoupling

When a view requires multiple transformed collections, permission flags, or formatted values, do not bloat controllers or embed queries in Blade. Use a dedicated ViewModel:

```php
namespace App\ViewModels;

use App\Models\User;
use Illuminate\Support\Collection;

class TenantDashboardViewModel
{
    public function __construct(public User $user) {}

    public function recentOrders(): Collection
    {
        return $this->user->orders()->latest()->take(5)->get();
    }

    public function canManageBilling(): bool
    {
        return $this->user->can('manage-billing');
    }
}
```
