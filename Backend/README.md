# Palm Backend

Laravel Modular Monolith Architecture with CQRS pattern and Unit of Work.

## Architecture

This backend follows a **Modular Monolith** architecture with **CQRS** (Command Query Responsibility Segregation) pattern:

### Core Layer
- **Database/UnitOfWork**: Centralized transaction management using Unit of Work pattern
- **Bus**: Command & Query Bus for handling business logic through handlers

### Modules
Each module is self-contained with four layers:
- **Domain**: Business entities and repository interfaces
- **Application**: Commands, Queries, and their Handlers
- **Infrastructure**: Repository implementations (Eloquent)
- **Presentation**: API Controllers (thin, delegate to handlers)

## Project Structure

```
Backend/
├── app/
│   ├── Core/                          # Shared infrastructure
│   │   ├── Database/                  # Unit of Work pattern
│   │   └── Bus/                       # Command & Query Bus
│   ├── Modules/                       # Independent modules
│   │   ├── Identity/                  # User management
│   │   │   ├── Domain/                # User entity, interfaces
│   │   │   ├── Application/           # Commands, Queries, Handlers
│   │   │   ├── Infrastructure/        # Eloquent repositories
│   │   │   └── Presentation/          # API Controllers
│   │   └── Sales/                     # Sales management
│   │       ├── Domain/                # Product, Order entities
│   │       ├── Application/           # Commands, Queries, Handlers
│   │       ├── Infrastructure/        # Eloquent repositories
│   │       └── Presentation/          # API Controllers
│   └── Providers/                     # Service providers
├── config/                            # Configuration files
├── database/                          # Database migrations
└── routes/                            # API routes
```

## Setup

1. Install dependencies:
```bash
composer install
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Configure database in `.env` file

5. Run migrations:
```bash
php artisan migrate
```

6. Start development server:
```bash
php artisan serve
```

## API Documentation

The API is fully documented using OpenAPI 3.0 specification.

### Interactive Documentation
Since Laravel doesn't include Swagger UI by default, we have two options:

#### Option 1: Standalone Swagger Server (Recommended for Development)
```bash
php -S localhost:8080 swagger.php
```
Then access: `http://localhost:8080/swagger`

#### Option 2: Native Laravel Integration (Requires Package Installation)
```bash
composer require darkaonline/l5-swagger
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate
```
Then access: `http://localhost:8000/api/documentation`

### OpenAPI Specification
The complete OpenAPI specification is available in `openapi.yaml` and can be accessed at:
- `http://localhost:8080/openapi.yaml` (when using standalone server)
- `http://localhost:8000/openapi.yaml` (when using Laravel integration)

### API Endpoints

#### Users (Identity Module)
- `GET /api/v1/users` - List all users (with pagination)
- `GET /api/v1/users/{id}` - Get user by ID
- `POST /api/v1/users` - Create new user
- `PUT /api/v1/users/{id}` - Update user
- `DELETE /api/v1/users/{id}` - Delete user

#### Products (Sales Module)
- `GET /api/v1/products` - List all products (with active filter)
- `GET /api/v1/products/{id}` - Get product by ID
- `POST /api/v1/products` - Create new product
- `PUT /api/v1/products/{id}` - Update product

#### Orders (Sales Module)
- `POST /api/v1/orders` - Create new order
- `PUT /api/v1/orders/{id}/status` - Update order status

### Authentication
The API uses JWT Bearer authentication. Include the token in the Authorization header:
```
Authorization: Bearer <your-jwt-token>
```

## Design Patterns

- **Unit of Work**: Centralized transaction management
- **CQRS**: Separate command and query handlers
- **Repository Pattern**: Abstract data access
- **Dependency Injection**: Laravel's service container
- **Modular Monolith**: Independent modules within single application
