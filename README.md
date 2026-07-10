# LapkingHub Laravel Foundation

This repository contains a fresh Laravel 12 foundation configured for PHP 8.3, Vite, and Bootstrap 5.

No ecommerce functionality has been added. This project is currently an architecture-ready Laravel foundation only: it does not include products, categories, orders, checkout, payment, shipping, or administration features.

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

## Enterprise Architecture Overview

The application keeps Laravel's default behavior intact while introducing explicit folders for common enterprise layers. These folders are intentionally lightweight so future features can be added without mixing responsibilities across controllers, persistence, domain objects, and infrastructure concerns.

## Folder Responsibilities

| Folder | Purpose |
| --- | --- |
| `app/Actions` | Single-purpose use-case classes. Actions coordinate one business operation and expose an `execute()` entry point. |
| `app/DTO` | Data Transfer Objects for moving structured data between layers without passing raw request arrays everywhere. |
| `app/Enums` | PHP enums for stable named values used by application code. |
| `app/Helpers` | Globally loaded helper functions. Keep helpers small, framework-safe, and side-effect free. |
| `app/Http/Controllers` | HTTP controllers and shared controller base classes. |
| `app/Interfaces` | General application contracts that do not belong to a more specific contracts folder. |
| `app/Repositories` | Shared repository abstractions and base persistence behavior. |
| `app/Repositories/Contracts` | Repository interfaces that define persistence contracts. |
| `app/Repositories/Eloquent` | Eloquent-backed repository implementations. |
| `app/Services` | Service-layer classes for reusable business workflows and orchestration. |
| `app/Traits` | Reusable PHP traits for cross-cutting behavior. |
| `app/Rules` | Custom validation rule objects. |
| `app/Observers` | Eloquent model observers. |
| `app/Policies` | Authorization policy classes. |
| `app/Exceptions` | Application-specific exceptions and exception organization. |
| `app/Support` | Framework-adjacent support utilities that are not global helpers. |
| `app/ValueObjects` | Immutable domain value objects for validated, self-contained values. |

## Base Classes

- `App\Http\Controllers\BaseController` provides consistent JSON response helpers for future API-style controllers.
- `App\Repositories\BaseRepository` provides a minimal Eloquent query foundation and a shared `find()` method.
- `App\Services\BaseService` is the root class for service-layer behavior.
- `App\Actions\BaseAction` defines the action contract shape through an abstract `execute()` method.

## Autoloading and Helpers

Laravel's default `App\` PSR-4 autoload mapping already covers the new application namespaces under `app/`. Composer also loads `app/Helpers/helpers.php` through the `autoload.files` section so global helper functions are available after Composer autoload generation.

After changing helper files, run:

```bash
composer dump-autoload
```

## Exception Structure

Application-level exceptions should live in `app/Exceptions`. `App\Exceptions\ApplicationException` is the base application exception and includes an HTTP status code accessor for future exception rendering while preserving Laravel's default exception handling.

## Architecture Rules

- Keep controllers thin; delegate work to actions or services.
- Depend on contracts from `app/Repositories/Contracts` when a service needs persistence.
- Place Eloquent-specific persistence details in `app/Repositories/Eloquent`.
- Use DTOs and value objects to avoid leaking unvalidated arrays across layers.
- Do not add feature modules until their requirements are explicitly requested.
