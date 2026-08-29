# Palm Frontend

Svelte application following MVC Architecture pattern.

## Architecture

This frontend follows a strict **MVC (Model-View-Controller)** architecture:

### Models
- Handle API communication and data transformation
- Provide clean interfaces for data operations
- Format API responses for application use

### Views
- Svelte components for UI rendering
- Handle user interactions and display logic
- Communicate with Controllers for business logic

### Controllers
- Manage application state and business logic
- Handle events and coordinate between Models and Views
- Provide clean interfaces for Views to consume

## Project Structure

```
Frontend/
└── src/
    ├── Models/                          # Data layer
    │   ├── api.js                       # API client configuration
    │   ├── UserModel.js                 # User data operations
    │   ├── ProductModel.js              # Product data operations
    │   └── OrderModel.js                # Order data operations
    ├── Views/                           # UI components
    │   ├── UserList.svelte              # User list view
    │   ├── ProductList.svelte          # Product list view
    │   └── OrderForm.svelte             # Order creation form
    ├── Controllers/                     # Business logic layer
    │   ├── UserController.js            # User business logic
    │   ├── ProductController.js         # Product business logic
    │   └── OrderController.js           # Order business logic
    ├── App.svelte                       # Main application component
    └── main.js                          # Application entry point
```

## Setup

1. Install dependencies:
```bash
npm install
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Configure API base URL in `.env` file

4. Start development server:
```bash
npm run dev
```

5. Build for production:
```bash
npm run build
```

## Features

### User Management
- List all users
- View user details
- Create new users
- Update user information
- Delete users

### Product Management
- List all products
- View product details
- Create new products
- Update product information
- Filter active products

### Order Management
- Create new orders
- Update order status
- Track order status changes

## Design Patterns

- **MVC Architecture**: Clear separation of concerns
- **Repository Pattern**: Models abstract API communication
- **Observer Pattern**: Svelte's reactivity system
- **Component-Based**: Modular UI components
- **State Management**: Controllers manage application state

## API Integration

The frontend communicates with the backend API through the configured API client. All API calls are centralized in the Models layer, ensuring consistent error handling and data transformation.

## Development

The application uses Vite for fast development and optimized production builds. Hot module replacement is enabled for rapid development.
