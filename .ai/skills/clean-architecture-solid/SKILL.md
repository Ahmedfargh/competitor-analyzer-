---
name: clean-architecture-solid
description: "Apply this skill when designing, structuring, or reviewing system architecture, enforcing SOLID principles, separating business logic from framework layers, or designing cohesive services, domain models, and interfaces. Triggers for architectural decisions, decoupling high-level policy from low-level details, dependency injection design, and modular boundaries."
license: MIT
metadata:
  category: software-engineering
---

# Clean Architecture & SOLID Principles

Comprehensive guide for designing maintainable, testable, and loosely coupled software systems by adhering to Clean Architecture layers and SOLID design principles.

---

## 1. SOLID Principles in Practice

### S — Single Responsibility Principle (SRP)
- **Definition:** A class or module should have one, and only one, reason to change. It should be responsible to a single actor or stakeholder.
- **Rules:**
  - Separate business calculation, data persistence, and HTTP presentation into distinct classes (e.g., Action/Service for logic, FormRequest for validation, Resource for presentation).
  - Watch out for classes named `*Manager`, `*Helper`, or `*Utils` that accumulate unrelated responsibilities.
  - If a class requires more than 4–5 injected dependencies, investigate whether it has too many responsibilities.

### O — Open/Closed Principle (OCP)
- **Definition:** Software entities should be open for extension, but closed for modification.
- **Rules:**
  - Favor polymorphism, strategy patterns, or event-driven listeners over cascading `switch` or `if/else` statements inspecting types.
  - Use interfaces or abstract contracts to define pluggable behaviors (e.g., payment gateways, notification channels, export formats).
  - Add new features by writing new classes implementing existing interfaces, rather than editing existing core logic.

### L — Liskov Substitution Principle (LSP)
- **Definition:** Subtypes must be substitutable for their base types without altering the correctness of the program.
- **Rules:**
  - Derived classes must not throw unexpected exceptions that the base interface does not specify.
  - Derived classes must honor the same pre-conditions (cannot be stricter) and post-conditions (cannot be weaker).
  - Do not implement empty or `throw new NotImplementedException()` methods just to satisfy an inherited interface.

### I — Interface Segregation Principle (ISP)
- **Definition:** Clients should not be forced to depend upon interfaces that they do not use.
- **Rules:**
  - Prefer multiple small, focused, role-based interfaces over one monolithic interface.
  - An interface should contain only the methods relevant to the consuming client (e.g., `CanBeNotified`, `Billable`, `Searchable`).
  - If an implementing class needs only 2 out of 10 methods declared on an interface, split the interface.

### D — Dependency Inversion Principle (DIP)
- **Definition:** High-level modules should not depend on low-level modules; both should depend on abstractions. Abstractions should not depend on details; details should depend on abstractions.
- **Rules:**
  - Depend on interfaces/contracts rather than concrete implementations for external boundaries (file systems, payment processors, external APIs, notifications).
  - Use Dependency Injection (constructor promotion) to supply dependencies rather than hardcoding instantiations (`new SomeService()`) or static access inside domain logic.
  - Bind abstractions to implementations via IoC container service providers.

---

## 2. Clean Architecture Layering

Clean architecture enforces the **Dependency Rule**: Source code dependencies must point inwards toward higher-level policies.

```
+-------------------------------------------------------------+
| Frameworks & Drivers (Web, DB, UI, Console, External APIs)  |
|   +-----------------------------------------------------+   |
|   | Interface Adapters (Controllers, Presenters, Jobs)  |   |
|   |   +---------------------------------------------+   |   |
|   |   | Application Business Rules (Use Cases /     |   |   |
|   |   | Actions / Commands / DTOs)                  |   |   |
|   |   |   +-------------------------------------+   |   |   |
|   |   |   | Enterprise Domain (Entities, Value   |   |   |   |
|   |   |   | Objects, Domain Events, Enums)      |   |   |   |
|   |   |   +-------------------------------------+   |   |   |
|   |   +---------------------------------------------+   |   |
|   +-----------------------------------------------------+   |
+-------------------------------------------------------------+
```

### Layer Responsibilities
1. **Domain Layer (Innermost):**
   - Pure domain models, Value Objects, Enums, Domain Exceptions.
   - Zero dependencies on HTTP, database tables, or framework classes.
   - Enforces business invariants and fundamental state transitions.
2. **Application / Use Cases Layer:**
   - Single-purpose Actions or Command Handlers representing user intents (e.g., `RegisterUserAction`, `ProcessOrderPayment`).
   - Orchestrates domain objects and calls out to abstracted repository/service contracts.
   - Accepts and returns Data Transfer Objects (DTOs), preventing framework request/response leaks.
3. **Interface Adapters:**
   - Translates external inputs (HTTP requests, CLI args, Webhook payloads) into application inputs.
   - Translates application outputs into HTTP JSON responses, view models, or event broadcasts.
4. **Framework & Drivers Layer (Outermost):**
   - Routing, ORM engine, Queue runners, Cache drivers, third-party SDKs.
   - Volatile, pluggable components that should be easily upgraded or swapped.

---

## 3. Architecture Anti-Patterns to Avoid

- **Fat Controllers:** Writing business logic, queries, and notification dispatches inside controller methods.
- **Anemic Domain Models:** Models that are just property bags with public setters and zero business rule enforcement.
- **Leaky Abstractions:** Passing Eloquent query builders or HTTP Request objects deep into domain services.
- **God Objects:** Monolithic classes that orchestrate everything in a single massive file.
- **Circular Dependencies:** Module A depends on Module B, while Module B imports Module A.

---

## 4. Verification & Architecture Review Checklist

- [ ] Does every new class have a single, clearly articulated responsibility?
- [ ] Are business-critical operations encapsulated in dedicated Use Cases / Actions rather than controllers?
- [ ] Are external third-party services accessed through abstracted interfaces?
- [ ] Is dependency injection used instead of direct instantiation or static calls?
- [ ] Can the core business logic be tested without booting a database or making live HTTP calls?
