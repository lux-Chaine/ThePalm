<?php

// Simple standalone PHP backend for Palm project
// This implements the modular monolith architecture without requiring full Laravel

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Simple routing
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Swagger UI Route
if ($requestUri === '/swagger.html' || $requestUri === '/swagger') {
    header('Content-Type: text/html');
    readfile(__DIR__ . '/public/swagger.html');
    exit;
}

// OpenAPI Spec Route
if ($requestUri === '/openapi.yaml' || $requestUri === '/openapi.json') {
    header('Content-Type: application/yaml');
    readfile(__DIR__ . '/openapi.yaml');
    exit;
}

// API Routes
if (strpos($requestUri, '/api/v1/') === 0) {
    header('Content-Type: application/json');
    
    $path = str_replace('/api/v1/', '', $requestUri);
    $segments = explode('/', trim($path, '/'));
    
    $module = $segments[0] ?? '';
    $action = $segments[1] ?? '';
    $id = $segments[2] ?? null;
    
    try {
        switch ($module) {
            case 'users':
                require_once __DIR__ . '/app/Modules/Identity/Presentation/UserController.php';
                $controller = new App\Modules\Identity\Presentation\UserController();
                handleUserRequest($controller, $requestMethod, $action, $id);
                break;
                
            case 'products':
                require_once __DIR__ . '/app/Modules/Sales/Presentation/ProductController.php';
                $controller = new App\Modules\Sales\Presentation\ProductController();
                handleProductRequest($controller, $requestMethod, $action, $id);
                break;
                
            case 'orders':
                require_once __DIR__ . '/app/Modules/Sales/Presentation/OrderController.php';
                $controller = new App\Modules\Sales\Presentation\OrderController();
                handleOrderRequest($controller, $requestMethod, $action, $id);
                break;
                
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Module not found']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Palm API - Use /api/v1/ endpoints']);
}

// Helper functions for handling requests
function handleUserRequest($controller, $method, $action, $id) {
    switch ($method) {
        case 'GET':
            if ($action === '' || $action === 'index') {
                echo json_encode($controller->index());
            } elseif ($id) {
                echo json_encode($controller->show($id));
            }
            break;
        case 'POST':
            if ($action === '' || $action === 'store') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($controller->store($data));
            }
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($controller->update($id, $data));
            }
            break;
        case 'DELETE':
            if ($id) {
                echo json_encode($controller->destroy($id));
            }
            break;
    }
}

function handleProductRequest($controller, $method, $action, $id) {
    switch ($method) {
        case 'GET':
            if ($action === '' || $action === 'index') {
                echo json_encode($controller->index());
            } elseif ($id) {
                echo json_encode($controller->show($id));
            }
            break;
        case 'POST':
            if ($action === '' || $action === 'store') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($controller->store($data));
            }
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($controller->update($id, $data));
            }
            break;
    }
}

function handleOrderRequest($controller, $method, $action, $id) {
    switch ($method) {
        case 'POST':
            if ($action === '' || $action === 'store') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($controller->store($data));
            }
            break;
        case 'PUT':
            if ($action === 'status' && $id) {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($controller->updateStatus($id, $data['status'] ?? 'pending'));
            }
            break;
    }
}
