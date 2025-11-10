# Symfony Project Management API (Clean Architecture / DDD-Inspired)

This Symfony REST API follows Clean Architecture principles with Domain-Driven Design (DDD) inspirations.
It emphasizes separation of concerns, testability, and scalability.

Rather than mixing logic inside controllers, the system is organized into explicit layers:

Controller → DTO → Mapper → Handler → Entity → Repository → Service

---

## Goal

This project demonstrates a professional Symfony architecture where each layer contributes to a clear and maintainable flow from HTTP request to database persistence.

Objectives include:

- Controllers: Handle HTTP input and delegate responsibilities to Handlers, keeping domain logic out.
- Handlers: Encapsulate business logic in dedicated classes, isolated from framework concerns.
- DTOs (Data Transfer Objects): Immutable, validated input objects that ensure strong typing and safe API boundaries before reaching the domain. 
- Mappers: Safely transform data between layers (DTO ↔ Entity ↔ Response), isolating conversion logic from business workflows. 
- Entities: Remain clean, persistence-focused, and free from framework or validation concerns. 
- Services: Handle external integrations, like logging or notifications, keeping side effects isolated from domain logic. 
- Repositories: Abstract database operations, decoupling domain logic from persistence.

---

## Architecture Overview

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

## Benefits

| Benefit | Explanation |
|--------|----------------|
| **Separation of Concerns** | Each layer has a focused responsibility |
| **Testability** | Handlers, Mappers, and DTOs can be tested in isolation |
| **Maintainability** | Adding features or fields requires minimal coupling |
| **Framework Independence** | Business logic does not depend on Symfony internals |
| **Scalability** | Handlers and Services can grow independently |
| **Type Safety** | DTOs and readonly objects prevent silent data issues |

---

## Installation

### Clone the repository

```bash
git clone https://github.com/barbara79/project-management-api.git
cd project-management-api
```

### Install dependencies
``` bash
docker compose up -d --build
```

## Author

Barbara Palumbo
Clean Architecture enthusiast • Software Developer
📧 barbara.palumbo79@gmail.com

🌐 linkedin.com/in/barbara-palumbo-b3356a18b

