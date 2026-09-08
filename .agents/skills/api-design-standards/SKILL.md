---
name: api-design-standards
description: "Apply this skill when designing, building, or reviewing RESTful APIs, HTTP endpoints, JSON schemas, and web service contracts. Triggers for URL structure and resource naming, HTTP verb selection (GET, POST, PUT, PATCH, DELETE), status code correctness, request validation errors, standard response envelopes, filtering, sorting, pagination, and API versioning."
license: MIT
metadata:
  category: software-engineering
---

# RESTful API Design Standards

Comprehensive standards for designing intuitive, robust, and industry-standard RESTful APIs and JSON web services.

---

## 1. URI & Resource Naming Conventions

- **Nouns, Not Verbs:** URIs identify resources, not actions.
  - Correct: `POST /api/v1/orders`
  - Incorrect: `POST /api/v1/createOrder`, `POST /api/v1/delete-user`
- **Plural Nouns:** Always use plural nouns for collections.
  - Correct: `/api/v1/users`, `/api/v1/products/42`
  - Incorrect: `/api/v1/user`, `/api/v1/product/42`
- **Hierarchical Relationships:** Use nested paths to reflect genuine parent-child ownership.
  - Correct: `GET /api/v1/authors/7/books` (Retrieve books belonging to author 7)
  - Limit nesting depth to at most 2 levels (e.g., avoid `/api/v1/companies/1/departments/2/teams/3/members`). Use top-level endpoints with IDs for deeper resources: `GET /api/v1/teams/3/members`.
- **Kebab-Case URLs:** Use lowercase kebab-case for multi-word URI segments.
  - Correct: `/api/v1/order-items`, `/api/v1/billing-addresses`
- **API Versioning:** Prefix API endpoints with major version number.
  - Pattern: `/api/v1/...`

---

## 2. HTTP Verbs & Idempotency

| Verb | Usage | Idempotent | Safe | Expected Success Code |
| :--- | :--- | :--- | :--- | :--- |
| **GET** | Retrieve a resource or collection | Yes | Yes | `200 OK` |
| **POST** | Create a new resource or initiate an operation | No | No | `201 Created` (with `Location` header) |
| **PUT** | Replace a resource completely | Yes | No | `200 OK` or `204 No Content` |
| **PATCH** | Partial update of resource attributes | No / Yes | No | `200 OK` |
| **DELETE** | Remove a resource | Yes | No | `204 No Content` or `200 OK` |

---

## 3. Standard HTTP Status Codes

Use precise HTTP status codes according to RFC standards:

### 2xx Success
- `200 OK`: Request succeeded (GET, PATCH, PUT, or DELETE with response body).
- `201 Created`: Resource successfully created (POST). Include newly created resource in response body.
- `204 No Content`: Request succeeded, no content to return (DELETE or action without response body).

### 4xx Client Errors
- `400 Bad Request`: Malformed syntax, unparseable JSON, or client-side semantic error.
- `401 Unauthorized`: Missing or invalid authentication token/credentials.
- `403 Forbidden`: Authenticated user does not have permission to access the requested resource.
- `404 Not Found`: Resource URI does not exist.
- `405 Method Not Allowed`: HTTP verb not supported for this endpoint.
- `409 Conflict`: Request conflicts with current resource state (e.g., unique constraint violation, concurrent modification).
- `422 Unprocessable Content`: Validation failed (syntactically valid JSON, but business/schema validation rules violated).
- `429 Too Many Requests`: Rate limit exceeded. Include `Retry-After` header.

### 5xx Server Errors
- `500 Internal Server Error`: Unexpected unhandled server exception. Never expose raw stack traces in production.
- `503 Service Unavailable`: Server is temporarily down for maintenance or overloaded.

---

## 4. Response Envelopes & Error Structure

### Single Resource Success (`200 OK` / `201 Created`)
```json
{
  "data": {
    "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
    "type": "users",
    "name": "Jane Doe",
    "email": "jane@example.com",
    "created_at": "2026-09-08T10:00:00Z"
  }
}
```

### Collection with Pagination (`200 OK`)
```json
{
  "data": [
    { "id": "1", "name": "Item A" },
    { "id": "2", "name": "Item B" }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 120,
    "last_page": 8
  },
  "links": {
    "first": "/api/v1/items?page=1",
    "last": "/api/v1/items?page=8",
    "prev": null,
    "next": "/api/v1/items?page=2"
  }
}
```

### Validation Error (`422 Unprocessable Content`)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required.",
      "The email must be a valid email address."
    ],
    "password": [
      "The password must be at least 8 characters."
    ]
  }
}
```

### General Error (`400`, `401`, `403`, `404`, `500`)
```json
{
  "error": {
    "code": "RESOURCE_NOT_FOUND",
    "message": "The requested resource could not be found."
  }
}
```

---

## 5. Filtering, Sorting & Pagination Standards

- **Pagination:** Always paginate collections. Prefer cursor-based pagination for high-volume streams (`?cursor=...`) and offset/page-based for admin tables (`?page=1&per_page=25`).
- **Filtering:** Use clear, prefixed query parameters:
  - Exact match: `?filter[status]=active`
  - Range or comparison: `?filter[created_after]=2026-01-01`
- **Sorting:** Use `sort` parameter, with `-` indicating descending order:
  - Ascending: `?sort=name`
  - Descending: `?sort=-created_at`
  - Multiple: `?sort=-created_at,name`
- **Field Sparseness (Sparse Fieldsets):**
  - Select specific fields: `?fields[users]=name,email`
