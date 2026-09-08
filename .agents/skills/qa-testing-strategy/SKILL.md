---
name: qa-testing-strategy
description: "Quality assurance (QA), exploratory test design, edge-case discovery, and robust testing strategy for Laravel applications. Use when designing end-to-end test matrices, performing boundary value analysis, verifying multi-tenant data isolation under concurrent loads, testing race conditions (pessimistic locking), validating API response contracts, designing failure/recovery modes, or organizing regression test suites."
license: MIT
metadata:
  author: laravel
---

# QA Testing Strategy & Quality Assurance

This skill guides test strategy, edge-case generation, boundary analysis, and multi-tenant quality assurance.

## Core QA Testing Dimensions

A complete test matrix covers five distinct quality dimensions:

1. **Happy Path**: The expected operational flow with standard, valid inputs.
2. **Boundary & Edge Values**: Off-by-one errors, min/max length strings, empty collections, zero values, extreme integers, unicode/multibyte characters.
3. **Authorization & Access Boundaries**: Unauthenticated guests, unauthorized roles, cross-tenant resource tampering, expired session tokens.
4. **Failure Modes & Negative Scenarios**: Network timeouts, external API outages, invalid file payloads, database constraint collisions.
5. **Concurrency & Race Conditions**: Simultaneous requests competing for the same resource (double-spending, over-booking, duplicate webhooks).

---

## Edge-Case & Boundary Value Analysis (BVA)

When testing any input field or business rule, test values at and immediately surrounding the boundary:

| Parameter Type | Test Cases |
|---|---|
| String length (e.g. 3-255) | 2 chars (fail), 3 chars (pass), 255 chars (pass), 256 chars (fail), empty string, whitespaces only |
| Numerical (e.g. quantity >= 1) | -1 (fail), 0 (fail), 1 (pass), max integer (pass/fail check) |
| Dates & Times | Leap years, timezones (UTC vs local), daylight savings transition, past vs future timestamps |
| Collections / Arrays | Empty array, single item, maximum batch size, duplicate elements within array |
| Characters & Encoding | Emojis (`🚀`), RTL Arabic/Hebrew text, null bytes (`\0`), SQL special characters (`'`, `"`, `--`) |

---

## Multi-Tenant Isolation Testing Matrix

Every multi-tenant feature must include negative isolation tests proving that Tenant A cannot access Tenant B's records:

```php
public function test_tenant_a_cannot_view_tenant_b_order(): void
{
    $tenantA = Tenant::create(['id' => 'company-a']);
    $tenantB = Tenant::create(['id' => 'company-b']);

    $orderB = $tenantB->run(fn () => Order::factory()->create());

    // Switch context to Tenant A
    tenancy()->initialize($tenantA);

    $response = $this->getJson("/api/orders/{$orderB->id}");

    // Must return 404 Not Found (or 403 Forbidden), never 200 OK
    $response->assertNotFound();

    tenancy()->end();
}
```

---

## Concurrency & Race Condition Verification

Test race conditions by simulating simultaneous database transactions using transaction locks (`lockForUpdate`):

```php
public function test_concurrent_inventory_deduction_prevents_overselling(): void
{
    $product = Product::factory()->create(['stock' => 1]);

    // First transaction acquires lock and deducts
    DB::transaction(function () use ($product) {
        $lockedProduct = Product::where('id', $product->id)->lockForUpdate()->first();
        $this->assertEquals(1, $lockedProduct->stock);
        $lockedProduct->decrement('stock');
    });

    $this->assertEquals(0, $product->fresh()->stock);

    // Second purchase attempt should throw or fail gracefully
    $this->expectException(InsufficientStockException::class);
    $service = app(OrderService::class);
    $service->purchase($product->id, 1);
}
```

---

## API Contract & Schema Assertions

Validate strict JSON schema structures, header assertions, and pagination metadata:

```php
$response = $this->getJson('/api/v1/projects');

$response->assertOk()
    ->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'title',
                'status',
                'created_at',
            ],
        ],
        'meta' => [
            'current_page',
            'last_page',
            'per_page',
            'total',
        ],
        'links' => [
            'first',
            'last',
            'prev',
            'next',
        ],
    ]);
```

---

## Test Flakiness Prevention

1. **Avoid `now()` without freezing**: Use `$this->travelTo(now())` or `$this->freezeTime()` whenever assertions depend on dates or elapsed time.
2. **Deterministic Factories**: Do not rely on random uniqueness when testing uniqueness constraints; specify distinct attributes explicitly:
   ```php
   User::factory()->create(['email' => 'unique_one@example.com']);
   ```
3. **Database Transactions**: Ensure tests use `Illuminate\Foundation\Testing\RefreshDatabase` or `DatabaseTransactions` so state never leaks between test executions.
