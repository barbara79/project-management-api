# 🧱 Symfony Project Management API (Clean Architecture)

This project is a **Symfony 7+ REST API** designed with **Clean Architecture** principles in mind — emphasizing **separation of concerns**, **testability**, and **scalability**.

Rather than placing all logic in controllers, it introduces distinct layers such as **DTOs**, **Handlers**, **Validators**, and **Mappers**.  
This structure mirrors how real-world enterprise Symfony applications are built.

---

## 🎯 Goal

The goal is to create a **maintainable**, **extensible**, and **testable** backend for project management — where each class has a single, well-defined responsibility.

---

## 🧠 Architectural Overview

### Why this structure?

| Layer | Responsibility | Why it's separate |
|--------|----------------|------------------|
| **Controller** | Handles HTTP requests and responses | Keeps framework-specific logic isolated |
| **DTO (Data Transfer Object)** | Represents incoming data (usually from JSON) | Prevents direct coupling between user input and your domain models |
| **Mapper** | Converts a DTO into a Doctrine Entity | Centralizes data transformation; avoids leaking persistence logic |
| **Handler** | Encapsulates business use cases (e.g. “create project”, “delete project”) | Makes the business layer reusable and testable outside controllers |
| **Validator** | Validates DTOs or entities | Keeps validation logic explicit and reusable |
| **Service** | External side effects (e.g. send notifications, emails, events) | Keeps business rules pure and side effects separated |
| **Entity** | Domain model stored in the database | Free from request logic and framework noise |

---

## 🏗️ Example Flow — Creating a Project

Here’s what happens when a client sends:

```json
{
  "title": "Website Revamp",
  "description": "UI/UX refresh for 2025",
  "deadline": "2025-12-01",
  "owner": "Alice"
}
```

1. Controller: Receives the request and delegates it.
2. DTO: Encapsulates and validates input data. Protects your domain from malformed or malicious input.
3. Mapper: Transforms the DTO into an Entity. Decouples API contracts from internal persistence logic.
4. Validator: Validates domain rules on the entity. Guarantees consistency before hitting the database.
5. Handler: Executes the business use case. Encapsulates the "Create Project" use case: reusable, testable, and isolated.

## ✅ Benefits of This Architecture

- Single Responsibility: Each class does one thing well.
- Testability: Handlers and Mappers can be tested without HTTP or Doctrine.
- Maintainability: Add new features (like sending emails, logging, etc.) by extending handlers/services.
- Framework Independence: Core logic doesn’t depend on Symfony. You could reuse the same business layer elsewhere.
- Explicit Data Contracts: DTOs make it clear what the API expects — versionable and documented.


## ⚙️ Installation

### 1. Clone the repository

```bash
git clone https://github.com/barbara79/project-management-api.git
cd project-management-api
```

### 2. Install dependencies
``` bash
docker compose up -d --build
```



## 📝 Author

Barbara Palumbo
Clean Architecture enthusiast • Symfony Developer
📧 barbara.palumbo79@example.com

🌐 linkedin.com/in/barbara-palumbo-b3356a18b

