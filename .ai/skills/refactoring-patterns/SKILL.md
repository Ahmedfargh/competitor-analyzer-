---
name: refactoring-patterns
description: "Apply this skill when planning or executing safe, step-by-step code refactoring without altering observable behavior. Triggers when simplifying complex methods, decomposing monolithic classes, eliminating duplicate logic, replacing conditionals with polymorphism or strategy patterns, and breaking down technical debt under a safety test harness."
license: MIT
metadata:
  category: software-engineering
---

# Refactoring Patterns & Clean Code

Systematic instructions and proven techniques for safe, incremental code refactoring without breaking existing features or altering system behavior.

---

## 1. The Core Refactoring Rules

1. **Rule of the Safety Net:** Never perform non-trivial refactoring without an existing, passing test suite. If tests do not exist, write characterization / regression tests first.
2. **Rule of Small Steps:** Refactor in microscopic, atomic steps. Make one structural transformation, run tests, verify green, and commit.
3. **Two Hats Rule:** Never add features and refactor at the same time. Keep the refactoring commits distinct from feature/bugfix commits.
4. **Observable Invariance:** The public contract and observable outcomes of the code must remain identical before and after each refactoring step.

---

## 2. Proven Refactoring Catalog

### 1. Extract Method / Function
- **When to use:** A code fragment is doing an identifiable task or has a comment explaining what it does.
- **Procedure:**
  1. Identify the block of statements and any local variables it reads or writes.
  2. Create a new method with an intention-revealing name describing *what* it does, not *how*.
  3. Pass read variables as arguments; return any modified variable.
  4. Replace the original code with a call to the extracted method.
  5. Run tests.

### 2. Extract Class / Action
- **When to use:** A class does two distinct jobs, or has a clump of properties and methods that always work together.
- **Procedure:**
  1. Define a new class with a focused responsibility.
  2. Move related fields and methods from the old class to the new class.
  3. In the old class, inject or instantiate the new class and delegate calls to it.
  4. Gradually migrate callers to the new class.
  5. Run tests.

### 3. Replace Conditional with Polymorphism / Strategy
- **When to use:** A `switch` or `if/else` block checks a type code or status to execute different algorithms.
- **Procedure:**
  1. Define an interface or abstract contract representing the operation (e.g., `PaymentGatewayInterface::charge()`).
  2. Create a concrete class for each branch implementing the interface.
  3. Replace the branching conditional with a lookup table, factory, or container resolution that returns the appropriate strategy instance.
  4. Invoke the method on the polymorphic instance.
  5. Run tests.

### 4. Introduce Parameter Object / DTO
- **When to use:** A method accepts 4+ parameters, or the same group of parameters is repeatedly passed together.
- **Procedure:**
  1. Define an immutable Data Transfer Object (DTO) or Value Object holding the parameters.
  2. Update the method signature to accept the DTO.
  3. Adapt the callers to construct and pass the DTO.
  4. Run tests.

### 5. Replace Nested Conditionals with Guard Clauses (Early Return)
- **When to use:** Deeply nested `if/else` blocks making it hard to find the "happy path" (arrow anti-pattern).
- **Procedure:**
  1. Identify edge cases, preconditions, and invalid states at the top of the function.
  2. Invert the condition and return or throw immediately (`guard clause`).
  3. Flatten the indentation for the remaining primary logic.
  4. Run tests.

### 6. Replace Magic Literals with Enums or Constants
- **When to use:** Raw numbers (e.g., `86400`, `30`) or hardcoded strings (e.g., `'active'`, `'cancelled'`) scattered through business logic.
- **Procedure:**
  1. Define a backed PHP Enum (or class constant) with descriptive naming.
  2. Replace all occurrences of the raw literal with the Enum case or constant.
  3. Type-hint method parameters using the Enum rather than raw primitives.
  4. Run tests.

---

## 3. Safe Refactoring Workflow (Step-by-Step)

```
[1. Baseline] Verify tests pass on unmodified code (Green)
       │
       ▼
[2. Plan] Identify specific code smell & target refactoring pattern
       │
       ▼
[3. Isolate] Make ONE small change (e.g., Extract Method)
       │
       ▼
[4. Verify] Run automated tests (Ensure Green, no regressions)
       │
       ▼
[5. Format] Run Pint / Linter on dirty files
       │
       ▼
[6. Commit] Atomic Git commit with clear intent (e.g., "refactor: extract BillingService")
       │
       ▼
[7. Repeat] Iterate until design is clean
```

---

## 4. Strangler Fig Pattern (For Legacy / Large Refactorings)

When refactoring a large legacy module or replacing a core subsystem:
1. Do not rewrite the entire system from scratch in a long-lived branch.
2. Build the new implementation alongside the old one.
3. Route a small slice of traffic or callers through the new implementation using an adapter or feature flag.
4. Gradually intercept and divert more responsibilities to the new system until the old implementation is completely unused.
5. Safely delete the old implementation.
