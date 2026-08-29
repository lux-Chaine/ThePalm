# Palm Application

A modern full-stack web application built with clean architecture principles, featuring a Laravel Modular Monolith backend and Svelte MVC frontend.

## 🏗️ Architecture

### Backend (Laravel - Modular Monolith)
- **Modular Monolith Architecture**: Independent modules (Identity, Sales) within a single application
- **CQRS Pattern**: Clean separation between Commands and Queries
- **Unit of Work**: Centralized transaction management
- **Repository Pattern**: Abstract data access layer
- **Clean Controllers**: Thin controllers that delegate to handlers

### Frontend (Svelte - MVC Architecture)
- **MVC Pattern**: Clear separation between Models, Views, and Controllers
- **Component-Based**: Reusable Svelte components
- **State Management**: Controllers manage application state
- **API Integration**: Clean Models for API communication

## 📁 Project Structure

```
Palm/
├── Backend/                                    # Laravel Modular Monolith
│   ├── app/
│   │   ├── Core/                               # Shared infrastructure
│   │   │   ├── Database/                       # Unit of Work pattern
│   │   │   └── Bus/                            # Command & Query Bus
│   │   └── Modules/                            # Independent modules
│   │       ├── Identity/                       # User management
│   │       │   ├── Domain/                     # Entities & interfaces
│   │       │   ├── Application/                # Commands, Queries, Handlers
│   │       │   ├── Infrastructure/             # Repository implementations
│   │       │   └── Presentation/               # API Controllers
│   │       └── Sales/                          # Sales management
│   │           ├── Domain/                     # Products, Orders entities
│   │           ├── Application/                # Commands, Queries, Handlers
│   │           ├── Infrastructure/             # Repository implementations
│   │           └── Presentation/               # API Controllers
│   ├── config/                                # Configuration files
│   ├── database/                              # Database migrations
│   ├── routes/                                # API routes
│   ├── openapi.yaml                           # OpenAPI specification
│   └── public/
│       └── swagger.html                        # Interactive API docs
│
└── Frontend/                                   # Svelte MVC Application
    └── src/
        ├── Models/                             # API integration layer
        ├── Views/                              # Svelte components
        └── Controllers/                        # Business logic layer
```

## 🚀 Getting Started

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- npm or yarn
- MySQL or compatible database

### Backend Setup

1. Navigate to the backend directory:
```bash
cd Backend
```

2. Install dependencies:
```bash
composer install
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=palm
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

6. Start the development server:
```bash
php artisan serve
```

The backend will be available at `http://localhost:8000`

### Frontend Setup

1. Navigate to the frontend directory:
```bash
cd Frontend
```

2. Install dependencies:
```bash
npm install
```

3. Configure environment:
```bash
cp .env.example .env
```

4. Update API base URL in `.env` if needed:
```env
VITE_API_BASE_URL=http://localhost:8000/api/v1
```

5. Start the development server:
```bash
npm run dev
```

The frontend will be available at `http://localhost:3000`

## 📚 API Documentation

### Interactive Documentation
- **Swagger UI**: `http://localhost:8000/swagger.html`
- **OpenAPI Spec**: Available in `Backend/openapi.yaml`

### Key Endpoints

#### Users (Identity Module)
- `GET /api/v1/users` - List all users
- `POST /api/v1/users` - Create new user
- `PUT /api/v1/users/{id}` - Update user
- `DELETE /api/v1/users/{id}` - Delete user

#### Products (Sales Module)
- `GET /api/v1/products` - List all products
- `POST /api/v1/products` - Create new product
- `PUT /api/v1/products/{id}` - Update product

#### Orders (Sales Module)
- `POST /api/v1/orders` - Create new order
- `PUT /api/v1/orders/{id}/status` - Update order status

## 🎯 Design Patterns & Principles

### Backend Patterns
- **Modular Monolith**: Independent modules within single application
- **CQRS**: Separate command and query handlers
- **Unit of Work**: Centralized transaction management
- **Repository Pattern**: Abstract data access
- **Dependency Injection**: Laravel's service container
- **SOLID Principles**: Single responsibility, Open-closed, etc.

### Frontend Patterns
- **MVC Architecture**: Model-View-Controller separation
- **Repository Pattern**: Models abstract API communication
- **Observer Pattern**: Svelte's reactivity system
- **Component-Based**: Modular UI components
- **State Management**: Controllers manage application state

## 🔧 Development

### Backend Development
```bash
cd Backend
php artisan serve          # Start development server
php artisan migrate         # Run database migrations
php artisan migrate:fresh   # Reset database
php artisan tinker          # Interactive REPL
```

### Frontend Development
```bash
cd Frontend
npm run dev                # Start development server
npm run build              # Build for production
npm run preview            # Preview production build
```

## 📝 Code Quality

### Backend
- **PSR-12 Coding Standards**
- **PHPStan Static Analysis**
- **PHPUnit Testing Framework**

### Frontend
- **ESLint JavaScript Linting**
- **Prettier Code Formatting**
- **Svelte Component Testing**

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👥 Team

- **Development Team**: Palm Development Team
- **Architecture**: Modular Monolith with CQRS
- **Frontend**: Svelte MVC Architecture

## 🙏 Acknowledgments

- Laravel Framework
- Svelte Framework
- OpenAPI Specification
- SOLID Principles
