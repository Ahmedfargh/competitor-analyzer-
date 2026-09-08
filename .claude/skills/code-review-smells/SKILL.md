---
name: code-review-smells
description: "Apply this skill during code reviews, PR audits, and quality assurance checks. Triggers when analyzing code for code smells, anti-patterns, maintainability issues, excessive cognitive or cyclomatic complexity, tight coupling, inappropriate intimacy, and dead code. Provides a comprehensive review checklist and structured severity rating."
license: MIT
metadata:
  category: software-engineering
---

# Code Review & Smells Guide

Comprehensive skill for identifying code smells, anti-patterns, architectural rot, and evaluating pull requests with actionable, high-signal engineering reviews.

---

## 1. Code Smell Taxonomy

### Bloaters (Code and classes that have ballooned uncontrollably)
- **Long Method (>25–30 lines):** A method doing more than one job. Difficult to read, debug, and test. *Fix: Extract Method.*
- **Large Class (>300 lines):** A class accumulating multiple reasons to change. *Fix: Extract Class or Use Cases.*
- **Primitive Obsession:** Using primitive types (strings, ints, arrays) instead of small domain objects (e.g., using `string $currency` and `int $cents` instead of a `Money` value object; associative arrays instead of typed DTOs). *Fix: Introduce Value Object or DTO.*
- **Long Parameter List (>3–4 parameters):** High cognitive overhead and prone to parameter swapping bugs. *Fix: Introduce Parameter Object / DTO.*
- **Data Clumps:** Groups of variables that frequently appear together across multiple places (e.g., `startDate`, `endDate`; `street`, `city`, `zipcode`). *Fix: Extract into a dedicated Value Object or Class.*

### Object-Orientation Abusers
- **Switch Statements / Nested Conditionals:** Complex branching based on object type or status code. *Fix: Replace Conditional with Polymorphism or Strategy Pattern.*
- **Refused Bequest:** A subclass inherits methods and data from a parent, but only uses a few and throws exceptions or ignores others. *Fix: Replace Inheritance with Delegation/Composition.*
- **Temporary Field:** An object variable that is only set and needed during specific algorithms or circumstances, leaving it null or invalid otherwise. *Fix: Extract Class or pass as method parameter.*

### Change Preventers
- **Divergent Change:** One class is commonly modified in different ways for different reasons (violates SRP). *Fix: Split class by actor or responsibility.*
- **Shotgun Surgery:** Making a single business change requires touching dozens of tiny edits across different classes. *Fix: Move related logic into a single cohesive module.*

### Couplers
- **Feature Envy:** A method accesses the data and methods of another object more than its own. *Fix: Move Method to the object whose data it is using.*
- **Inappropriate Intimacy:** Classes that depend heavily on each other's private/protected implementation details. *Fix: Introduce abstractions or encapsulation.*
- **Message Chains (Law of Demeter violation):** Long chains like `$order->getCustomer()->getAddress()->getZipCode()`. *Fix: Hide Delegate or provide direct convenience methods.*

### Dispensables
- **Dead Code:** Unreachable code, unused methods, commented-out code blocks, or unused parameters. *Fix: Delete immediately; git history preserves it.*
- **Speculative Generality:** Abstractions, interfaces, or configurations added for "future requirements" that don't exist yet (YAGNI violation). *Fix: Delete speculative layers and implement what is needed now.*
- **Duplicate Code (DRY violation):** Identical or subtly duplicated logic duplicated across multiple controllers, jobs, or services. *Fix: Extract shared helper, trait, or service.*

---

## 2. Review Severity Matrix

When reviewing code or assessing pull requests, categorize findings by severity:

| Severity | Definition | Action Required |
| :--- | :--- | :--- |
| **P0: Blocker** | Security vulnerability, data loss risk, regression, critical concurrency bug, or broken functionality | Must fix before merge |
| **P1: Major** | Violation of architectural boundaries, unhandled edge cases, N+1 queries, significant maintainability risk | Must fix unless explicitly documented and deferred |
| **P2: Minor** | Suboptimal naming, minor code smell, missing unit test coverage on non-critical path | Recommended fix |
| **P3: Nitpick** | Formatting preference, minor stylistic suggestion, non-blocking thought | Optional; do not block merge |

---

## 3. Systematic Code Review Checklist

### A. Correctness & Functionality
- [ ] Does the code satisfy the user requirements and handle empty/null/boundary states?
- [ ] Are race conditions, concurrency conflicts, or idempotency concerns addressed?
- [ ] Are transactions wrapped around multi-step database writes?

### B. Security & Input Sanitization
- [ ] Are inputs strictly validated with strong type checking and authorization checks?
- [ ] Is data escaping/sanitizing correctly applied to prevent SQL injection, XSS, or command injection?
- [ ] Are sensitive fields (tokens, passwords, PII) hidden from logs, error reports, and API serialization?

### C. Performance & Scalability
- [ ] Are there hidden N+1 queries or queries executed inside loops?
- [ ] Are proper database indexes present for columns in `WHERE`, `ORDER BY`, and `JOIN` clauses?
- [ ] Are large dataset operations batched, streamed, or chunked?

### D. Readability & Maintainability
- [ ] Do class and variable names clearly communicate intent without relying on cryptic abbreviations?
- [ ] Is cognitive complexity low (nesting depth <= 2–3 levels, early returns used)?
- [ ] Are magic numbers and strings replaced by named constants or Enums?
- [ ] Is all dead and commented-out code removed?
