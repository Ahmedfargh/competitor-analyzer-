---
name: cybersecurity-audit-hardening
description: "Application cybersecurity, OWASP Top 10 mitigation, and defensive hardening for Laravel applications. Use when auditing code for security vulnerabilities (SQLi, XSS, CSRF, IDOR, SSRF, Mass Assignment), securing multi-tenant boundaries and data isolation, configuring rate limiting and brute-force defenses, securing HTTP headers (CSP, HSTS, CORS), hardening authentication/session lifecycles, safely handling file uploads, and managing cryptographic secrets."
license: MIT
metadata:
  author: laravel
---

# Cybersecurity & Application Hardening

This skill establishes defensive security standards and vulnerability mitigations across Laravel applications, multi-tenant boundaries, and public API surfaces.

## OWASP Top 10 Defenses in Laravel

### 1. Injection (SQLi)
- Always use Eloquent or parameterized bindings with Query Builder (`whereRaw('status = ?', [$status])`).
- **Never** concatenate unvalidated user input into raw expressions:
  ```php
  // DANGEROUS:
  DB::select("SELECT * FROM users WHERE email = '{$input}'");

  // SECURE:
  DB::select("SELECT * FROM users WHERE email = ?", [$input]);
  ```
- Guard against column-name injection in dynamic sorting:
  ```php
  $allowedSorts = ['id', 'name', 'created_at'];
  $sortBy = in_array($request->query('sort'), $allowedSorts, true) ? $request->query('sort') : 'id';
  ```

### 2. Cross-Site Scripting (XSS)
- Default to double curly braces in Blade: `{{ $content }}` (auto-escaped with `htmlspecialchars`).
- Treat `{!! $html !!}` with extreme caution. If raw HTML is unavoidable, sanitize with a trusted HTML sanitizer before rendering.
- For JSON in scripts, always use `@json($data)` or `Js::from($data)`.

### 3. Cross-Site Request Forgery (CSRF)
- Ensure all state-changing routes (`POST`, `PUT`, `PATCH`, `DELETE`) require `@csrf` or the `X-CSRF-TOKEN` header.
- Never place mutating operations on `GET` routes.

### 4. Mass Assignment Vulnerabilities
- Explicitly define `$fillable` on all Eloquent models. Never unguard models globally without strict Form Request validation.
- Always use `$request->validated()` instead of `$request->all()` when creating or updating models:
  ```php
  // SECURE:
  User::create($request->validated());
  ```

### 5. Insecure Direct Object References (IDOR) & Broken Access Control
- Always authorize actions using Laravel Policies:
  ```php
  $this->authorize('update', $order);
  ```
- Scope queries to the authenticated user or tenant:
  ```php
  $order = $request->user()->orders()->findOrFail($orderId);
  ```

---

## Multi-Tenant Security & Isolation

1. **Context Hijacking**: Never rely on client-provided tenant identifiers (e.g. hidden inputs or headers) when domain or authenticated session tenancy is configured.
2. **Cross-Tenant IDOR**: In database-per-tenant, ensure every model operation runs inside initialized tenant context. In shared databases, enforce global tenant scopes.
3. **Queue Isolation**: Ensure background jobs serialize the tenant context (`TenantAwareJob`) so queued workers cannot access another tenant's records.

---

## Authentication, Passwords & Rate Limiting

1. **Password Hashing**: Use Argon2id or strong Bcrypt (`config/hashing.php`).
2. **Brute-Force Protection**: Enforce rate limiting on all login, registration, password reset, and sensitive API endpoints:
   ```php
   RateLimiter::for('login', function (Request $request) {
       return Limit::perMinute(5)->by($request->ip() . $request->input('email'));
   });
   ```
3. **Session Security**:
   - `SESSION_SECURE_COOKIE=true` in production (HTTPS only).
   - `SESSION_HTTP_ONLY=true` (prevents JavaScript access to cookies).
   - `SESSION_SAME_SITE=lax` or `strict`.

---

## Secure HTTP Headers & Transport

Enforce security headers via middleware:
- **Strict-Transport-Security (HSTS)**: `max-age=31536000; includeSubDomains`
- **X-Content-Type-Options**: `nosniff`
- **X-Frame-Options**: `SAMEORIGIN` or `DENY`
- **Referrer-Policy**: `strict-origin-when-cross-origin`
- **Content-Security-Policy (CSP)**: Disallow inline scripts without nonces or hashes.

---

## File Upload Defense

1. **MIME Type & Magic Byte Validation**: Do not trust file extension alone; validate MIME type and size in Form Requests:
   ```php
   'avatar' => 'required|file|image|mimes:jpeg,png,webp|max:2048',
   ```
2. **Storage Isolation**: Store uploaded files outside public docroot or on an S3 bucket with private ACLs; serve through signed temporary URLs or streaming controller responses.
3. **Executable Extension Neutralization**: Never allow uploads with `.php`, `.phar`, `.phtml`, `.exe`, or `.sh` extensions.

---

## Cryptography & PII Masking

- Never store sensitive secrets, API keys, or access tokens unencrypted.
- Use Eloquent encrypted casts:
  ```php
  protected function casts(): array
  {
      return [
          'tax_id' => 'encrypted',
          'secret_key' => 'encrypted',
      ];
  }
  ```
- Mask sensitive credentials, credit card numbers, and passwords in application logs and exception traces.
