---
name: livewire-development
description: "Laravel Livewire v3 and Volt reactive component development. Use when building dynamic, reactive frontends in Laravel without full SPA frameworks; creating class-based or Volt components; handling forms, real-time validation, and data binding (wire:model); listening to WebSocket Echo events in real-time; optimizing performance (wire:key, #[Lazy]); and integrating Livewire within modular architecture."
license: MIT
metadata:
  author: laravel
---

# Livewire Development

This skill provides best practices and conventions for building interactive, reactive applications with **Laravel Livewire v3** and **Volt**.

## Core Principles

1. **Server-Driven Reactivity**: Livewire components run on the server and update the DOM via AJAX/WebSockets. Always validate and authorize actions on the server.
2. **Lean State**: Only put minimal state in public component properties. Avoid exposing entire Eloquent models when only an ID or specific attributes are needed.
3. **Optimized Network Payloads**: Use debounce/lazy modifiers on `wire:model` to avoid unnecessary network requests on every keystroke.

---

## Component Structure

### Class-Based Components

```php
namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class PostIndex extends Component
{
    use WithPagination;

    #[Locked]
    public int $categoryId;

    #[Validate('required|string|min:3|max:255')]
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deletePost(int $postId): void
    {
        $post = Post::findOrFail($postId);
        $this->authorize('delete', $post);

        $post->delete();
        $this->dispatch('post-deleted', id: $postId);
    }

    public function render()
    {
        return view('livewire.post-index', [
            'posts' => Post::where('category_id', $this->categoryId)
                ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->paginate(10),
        ]);
    }
}
```

---

## Data Binding & Forms

- **Standard binding**: `wire:model="title"` (updates on blur/change).
- **Live updates**: `wire:model.live="search"` or `wire:model.live.debounce.300ms="search"` for search inputs.
- **Form Objects**: For complex forms, extract properties and validation rules into a dedicated `Livewire\Form` class:
  ```php
  use Livewire\Form;

  class PostForm extends Form
  {
      #[Validate('required|min:5')]
      public string $title = '';

      #[Validate('required')]
      public string $body = '';
  }
  ```

---

## Real-Time Echo Broadcasting Integration

Livewire v3 has native support for listening to Laravel Echo broadcast events directly inside components:

```php
use Livewire\Attributes\On;

class OrderStatus extends Component
{
    public int $orderId;
    public string $status;

    #[On('echo:orders.{orderId},OrderStatusUpdated')]
    public function handleStatusUpdate(array $event): void
    {
        $this->status = $event['status'];
    }

    #[On('echo-private:tenant.{tenantId}.alerts,NewAlert')]
    public function handleTenantAlert(array $event): void
    {
        $this->dispatch('notify', message: $event['message']);
    }
}
```

---

## Performance & Optimization

1. **Always Use `wire:key`**: In every `@foreach` loop rendering Livewire DOM elements:
   ```blade
   @foreach ($posts as $post)
       <div wire:key="post-{{ $post->id }}">
           <h3>{{ $post->title }}</h3>
       </div>
   @endforeach
   ```
2. **Lazy Loading**: Use `#[Lazy]` on heavy components so initial page load remains instant:
   ```php
   use Livewire\Attributes\Lazy;

   #[Lazy]
   class HeavyMetricsChart extends Component
   {
       public function placeholder()
       {
           return view('livewire.placeholders.skeleton-chart');
       }
   }
   ```
3. **Navigation**: Use `wire:navigate` on links to achieve SPA-speed page transitions:
   ```blade
   <a href="{{ route('posts.index') }}" wire:navigate>All Posts</a>
   ```

---

## Security

1. **Attribute Protection**: Use `#[Locked]` on properties that should never be manipulated from the client (IDs, tenant identifiers, flags).
2. **Authorization**: Never trust client action dispatches. Always call `$this->authorize('action', $model)` inside Livewire action methods.
3. **Do Not Store Sensitive Secrets in Public State**: Anything in a public property is serialized and exposed in the browser network tab.
