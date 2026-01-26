# Symfony Project Management API (Clean Architecture / DDD-Inspired)


This project is a backend API designed to demonstrate how to build a **scalable, testable, and maintainable Symfony application** using Clean Architecture principles with Domain-Driven Design (DDD) inspiration.

The focus is not on delivering a full product, but on showcasing **professional backend architecture**, clear separation of concerns, and production-oriented design decisions.

---

## Project Purpose

The goal of this project is to demonstrate:

- How to structure a Symfony API beyond traditional MVC
- How to isolate business logic from framework concerns
- How to design backend systems that are easy to test, evolve, and scale
- How to model business use cases using explicit application layers

This repository is intended as a **technical portfolio project** for backend-oriented roles.

---

## 🚧 Project Status

⚠️ **Work in progress**

Core architectural patterns are implemented and stable.  
Some interactions between domain concepts (e.g. Projects ↔ Tasks workflows) are intentionally incomplete and planned for future iterations.

This reflects a realistic development approach where:
- architecture is validated early
- features evolve incrementally
- domain complexity grows over time

---

## Architectural Overview

The application follows a Clean Architecture approach with explicit application layers:

Controller → DTO → Mapper → Handler → Entity → Repository → Service

This structure enforces strict separation of concerns and ensures that business logic
remains isolated from framework and infrastructure details.

---

## Layer Responsibilities

| Layer | Responsibility | Why It Exists |
|--------|----------------|----------------|
| **Controller** | Handles HTTP requests and delegates to Handlers | Isolates framework-specific logic from domain logic |
| **DTO** | Represents and validates input data | Provides immutability, strong typing, and safe API boundaries |
| **Mapper** | Transforms data between layers | Centralizes conversion logic and prevents silent data inconsistencies |
| **Handler** | Executes a specific business use case | Orchestrates validation, persistence, and side effects |
| **Repository** | Manages entity persistence via Doctrine | Abstracts database operations, keeping entities persistence-agnostic |
| **Entity** | Core domain model representation | Free from validation or infrastructure logic |
| **Service** | Handles side effects (logging, notifications, integrations) | Keeps domain logic predictable and testable |
| **Exception Layer** | Custom domain-specific exceptions | Provides consistent error handling across layers |

---

## 🔁 Request Flow

1. **Controller** receives an HTTP request and delegates execution
2. **DTO** validates and encapsulates input data
3. **Mapper** converts DTOs into domain entities
4. **Handler** executes the business use case
5. **Repository** persists or retrieves entities
6. **Service** handles side effects (logging, notifications, etc.)
7. **Controller** returns a structured JSON response

---

## Flow Explanation

- **Controller:** Receives HTTP input, delegates to Handlers, parses requests, formats JSON responses. No domain logic.  
- **DTO:** Immutable, validated input objects that ensure strong typing before reaching the domain.  Immutable, validated objects ensure strong typing and safe API boundaries.
- **Mapper:** Safely converts between representations (Request → DTO → Entity → Response). Raises domain exceptions on failure.  
- **Handler:** Contains main business logic, coordinates mapping, persistence, and service calls, and maintains transaction boundaries.  
- **Entity:** Represents the domain model, containing only relevant data and relationships.  
- **Repository:** Encapsulates database access, providing persistence abstraction.  
- **Service:** Services handle external integrations like email or logging, highlighting that side effects are isolated from domain logic.
- **Exception Handling:** Standardized, domain-specific exceptions ensure predictable error management.

---

## Key Benefits

- **Separation of Concerns** — each layer has a focused responsibility
- **Testability** — handlers, mappers, and DTOs are easily testable
- **Maintainability** — changes are localized and predictable
- **Framework Independence** — business logic is not tied to Symfony
- **Scalability** — architecture supports growing complexity
- **Type Safety** — DTOs and readonly objects prevent silent bugs

---

## 🧪 Testing

The project uses **Pest** for automated testing.

Run test coverage:
```bash
./vendor/bin/pest --coverage
```

Load fixtures for testing:
``` bash
php bin/console doctrine:fixtures:load --env=test --no-interaction
```

Run tests:
```bash
./vendor/bin/pest
```

## Installation

```bash
git clone https://github.com/barbara79/project-management-api.git


cd project-management-api

docker compose up -d --build
```

## Author

Barbara Palumbo 

Clean Architecture enthusiast • Software Developer

🌐 linkedin.com/in/barbara-palumbo-b3356a18b

