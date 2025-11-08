# Symfony Project Management API (Clean Architecture)



This project is a **Symfony REST API** designed following **Clean Architecture** principles — emphasizing **separation of concerns**, **scalability**, and **testability**.

Rather than mixing logic inside controllers, this application defines a clear flow:
> **Controller → DTO → Mapper → Handler → Validator → Entity → NotificationService**

Each layer has a single, well-defined responsibility.

---

## Goal

To demonstrate a professional Symfony architecture where each class does one thing:
- Controllers manage HTTP only
- Business logic lives in Handlers
- Validation is centralized
- Data is clearly defined through DTOs
- Entities remain clean and persistence-focused

This mirrors modern enterprise Symfony practices used in large codebases.

---

## Architecture Overview

This project follows a clean, layered architecture built for clarity, testability, and maintainability.
Each layer communicates through well-defined interfaces.

| Layer | Responsibility | Why It Exists |
|--------|----------------|----------------|
| **Controller** | Handles HTTP requests/responses | Keeps framework concerns separate |
| **DTO (Data Transfer Object)** | Represents input data (JSON → PHP object) | Ensures strong typing and input validation |
| **Mapper** | Converts DTO → Entity | Keeps transformation logic isolated |
| **Validator** | Validates entities using Symfony Constraints | Enforces domain rules and throws structured exceptions |
| **Handler** | Orchestrates the use case (e.g. create project) | Encapsulates the business workflow |
| **Entity** | Represents the persisted domain model | Free of API or validation logic |
| **Service (NotificationService)** | Handles side effects (emails, logs, etc.) | Keeps business rules pure and testable |

---

### Controller

Lightweight entry point responsible for deserializing requests, invoking the correct handler, and normalizing responses.
No domain or persistence logic should live here.

### DTO (Data Transfer Object)

Encapsulates and validates input data.
DTOs are immutable and strongly typed (readonly), ensuring that only valid, structured data enters the application.
They protect the domain from malformed or malicious input.

### Mapper

Transforms data between layers (e.g. DTO → Entity).
It acts as a bridge between the transport and domain layers, handling type conversions (like string → DateTimeImmutable).

### Handler

Implements the business use case — orchestrating validation, persistence, and notifications.
Handlers contain pure business logic, isolated from Symfony or HTTP concerns, and are easy to test in isolation.

### Validator

Ensures entities are valid before persistence, using Symfony’s ValidatorInterface.
It throws structured exceptions if constraints are violated — guaranteeing consistent error handling.

### Service

Encapsulates reusable, side-effect logic (e.g., logging, notifications, or external integrations).
Keeps the core business flow clean and framework-independent.

### Entity (Domain Model)

Represents the core business object (e.g. Project).
Contains only domain-relevant properties and relationships — free from controller or validation code.
Entities stay pure and persistence-focused.

## Benefits

| Benefit | Explanation |
|--------|----------------|
| Separation of Concerns |	Each layer has a single purpose |
| Testability	Handlers, Mappers, and Validators | can be unit-tested |
| Maintainability	| New fields or logic can be added with minimal coupling |
| Framework Independence |	Business logic doesn’t rely on Symfony internals |
| Scalability |	Handlers and Services can grow independently |
| Strong Typing	| DTOs and readonly objects prevent silent data corruption |


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
Clean Architecture enthusiast • Symfony Developer
📧 barbara.palumbo79@gmail.com

🌐 linkedin.com/in/barbara-palumbo-b3356a18b

