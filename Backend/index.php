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
if ($requestUri === '/' || $requestUri === '/swagger.html' || $requestUri === '/swagger') {
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
if (strpos($requestUri,'/api/v1/') === 0) {
    header('Content-Type: application/json');
    
    $path = str_replace('/api/v1/', '', $requestUri);
    $segments = explode('/', trim($path, '/'));
    
    $module = $segments[0] ?? '';
    $action = $segments[1] ?? '';
    $id = $segments[2] ?? null;
    
    try {
        // Initialize CommandBus and QueryBus
        require_once __DIR__ . '/app/Core/Bus/CommandBus.php';
        require_once __DIR__ . '/app/Core/Bus/QueryBus.php';
        require_once __DIR__ . '/app/Core/Bus/CommandInterface.php';
        require_once __DIR__ . '/app/Core/Bus/QueryInterface.php';
        require_once __DIR__ . '/app/Core/Bus/CommandHandlerInterface.php';
        require_once __DIR__ . '/app/Core/Bus/QueryHandlerInterface.php';
        
        // Load exception handlers
        require_once __DIR__ . '/app/Core/Exceptions/GlobalExceptionHandler.php';
        require_once __DIR__ . '/app/Core/Exceptions/ValidationException.php';
        require_once __DIR__ . '/app/Core/Exceptions/NotFoundException.php';
        require_once __DIR__ . '/app/Core/Exceptions/UnauthorizedException.php';
        require_once __DIR__ . '/app/Core/Exceptions/ForbiddenException.php';
        require_once __DIR__ . '/app/Core/Exceptions/BadRequestException.php';
        require_once __DIR__ . '/app/Core/Exceptions/BusinessRuleException.php';
        require_once __DIR__ . '/app/Core/Exceptions/ConflictException.php';
        
        $commandBus = new App\Core\Bus\CommandBus();
        $queryBus = new App\Core\Bus\QueryBus();
        
        switch ($module) {
            case 'users':
                require_once __DIR__ . '/app/Modules/Identity/Presentation/UserController.php';
                $controller = new App\Modules\Identity\Presentation\UserController($commandBus, $queryBus);
                handleUserRequest($controller, $requestMethod, $action, $id);
                break;
                
            case 'rooms':
                require_once __DIR__ . '/app/Modules/Rooms/Presentation/RoomController.php';
                $controller = new App\Modules\Rooms\Presentation\RoomController($commandBus, $queryBus);
                handleRoomRequest($controller, $requestMethod, $action, $id);
                break;
                
            case 'reservations':
                require_once __DIR__ . '/app/Modules/Reservations/Presentation/ReservationController.php';
                $controller = new App\Modules\Reservations\Presentation\ReservationController($commandBus, $queryBus);
                handleReservationRequest($controller, $requestMethod, $action, $id);
                break;
                
            case 'invoices':
                require_once __DIR__ . '/app/Modules/Accounting/Presentation/InvoiceController.php';
                $controller = new App\Modules\Accounting\Presentation\InvoiceController($commandBus, $queryBus);
                handleInvoiceRequest($controller, $requestMethod, $action, $id);
                break;
                
            case 'expenses':
                require_once __DIR__ . '/app/Modules/Accounting/Presentation/ExpenseController.php';
                $controller = new App\Modules\Accounting\Presentation\ExpenseController($commandBus, $queryBus);
                handleExpenseRequest($controller, $requestMethod, $action, $id);
                break;

            case 'guests':
                require_once __DIR__ . '/app/Modules/Guests/Presentation/GuestController.php';
                $controller = new App\Modules\Guests\Presentation\GuestController($commandBus, $queryBus);
                handleGuestRequest($controller, $requestMethod, $action, $id);
                break;

            case 'reports':
                require_once __DIR__ . '/app/Modules/Reports/Presentation/ReportController.php';
                $controller = new App\Modules\Reports\Presentation\ReportController($commandBus, $queryBus);
                handleReportRequest($controller, $requestMethod, $action);
                break;

            case 'settings':
                require_once __DIR__ . '/app/Modules/Settings/Presentation/SettingController.php';
                $controller = new App\Modules\Settings\Presentation\SettingController($commandBus, $queryBus);
                handleSettingRequest($controller, $requestMethod, $action, $id);
                break;

            default:
                http_response_code(404);
                echo json_encode(['error' => 'Module not found']);
        }
    } catch (Throwable $e) {
        $errorResponse = App\Core\Exceptions\GlobalExceptionHandler::handleWithLogging($e);
        http_response_code($errorResponse['status_code'] ?? 500);
        echo json_encode($errorResponse);
    }
} else {
    echo json_encode(['message' => 'Palm API - Use /api/v1/ endpoints']);
}

// Permission checking function
function checkPermission(string $requiredPermission): bool
{
    try {
        // Load middleware classes
        require_once __DIR__ . '/app/Core/Middleware/JWTMiddleware.php';
        require_once __DIR__ . '/app/Core/Middleware/PermissionMiddleware.php';
        
        App\Core\Middleware\PermissionMiddleware::check($requiredPermission);
        return true;
    } catch (App\Core\Exceptions\UnauthorizedException $e) {
        http_response_code(401);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    } catch (App\Core\Exceptions\ForbiddenException $e) {
        http_response_code(403);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Helper functions for handling requests
function handleUserRequest($controller, $method, $action, $id) {
    // Permission checks
    if ($method === 'POST' && $action === 'login') {
        // Login doesn't require permission
    } elseif ($method === 'GET' && ($action === '' || $action === 'index')) {
        if (!checkPermission('users.view')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'POST' && ($action === '' || $action === 'store')) {
        if (!checkPermission('users.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'PUT' && $id) {
        if (!checkPermission('users.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'DELETE' && $id) {
        if (!checkPermission('users.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    }

    switch ($method) {
        case 'GET':
            if ($action === '' || $action === 'index') {
                $request = new App\Core\Http\Request();
                echo json_encode($controller->index($request));
            } elseif ($action === 'roles') {
                echo json_encode($controller->getRoles());
            } elseif ($id && $action === 'permissions') {
                echo json_encode($controller->getUserPermissions($id));
            } elseif ($id) {
                echo json_encode($controller->show($id));
            }
            break;
        case 'POST':
            if ($action === 'login') {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->login($request));
            } elseif ($action === 'refresh-token') {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->refreshToken($request));
            } elseif ($action === '' || $action === 'store') {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->store($request));
            }
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->update($request, $id));
            }
            break;
        case 'DELETE':
            if ($id) {
                echo json_encode($controller->destroy($id));
            }
            break;
    }
}

function handleRoomRequest($controller, $method, $action, $id) {
    // Permission checks
    if ($method === 'GET' && ($action === '' || $action === 'index')) {
        if (!checkPermission('rooms.view')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'POST' && ($action === '' || $action === 'store')) {
        if (!checkPermission('rooms.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'PUT' && $id) {
        if (!checkPermission('rooms.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    }

    switch ($method) {
        case 'GET':
            if ($action === '' || $action === 'index') {
                $request = new App\Core\Http\Request($_GET);
                echo json_encode($controller->index($request));
            } elseif ($id) {
                echo json_encode($controller->show($id));
            }
            break;
        case 'POST':
            if ($action === '' || $action === 'store') {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->store($request));
            }
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->update($request, $id));
            }
            break;
    }
}

function handleReservationRequest($controller, $method, $action, $id) {
    // Permission checks
    if ($method === 'GET' && ($action === '' || $action === 'index')) {
        if (!checkPermission('reservations.view')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'POST' && ($action === '' || $action === 'store')) {
        if (!checkPermission('reservations.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'PUT' && $id) {
        if (!checkPermission('reservations.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    }

    switch ($method) {
        case 'GET':
            if ($action === '' || $action === 'index') {
                $request = new App\Core\Http\Request($_GET);
                echo json_encode($controller->index($request));
            } elseif ($id) {
                echo json_encode($controller->show($id));
            }
            break;
        case 'POST':
            if ($action === '' || $action === 'store') {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->store($request));
            }
            break;
        case 'PUT':
            if ($action === 'status' && $id) {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->updateStatus($request, $id));
            }
            break;
    }
}

function handleInvoiceRequest($controller, $method, $action, $id) {
    // Permission checks
    if ($method === 'GET' && ($action === '' || $action === 'index')) {
        if (!checkPermission('invoices.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'POST' && ($action === '' || $action === 'store')) {
        if (!checkPermission('invoices.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'PUT' && $id) {
        if (!checkPermission('invoices.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    }

    switch ($method) {
        case 'GET':
            if ($action === '' || $action === 'index') {
                $request = new App\Core\Http\Request($_GET);
                echo json_encode($controller->index($request));
            } elseif ($id) {
                echo json_encode($controller->show($id));
            }
            break;
        case 'POST':
            if ($action === '' || $action === 'store') {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->store($request));
            }
            break;
        case 'PUT':
            if ($action === 'payment' && $id) {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->updatePayment($request, $id));
            }
            break;
    }
}

function handleExpenseRequest($controller, $method, $action, $id) {
    // Permission checks
    if ($method === 'GET' && ($action === '' || $action === 'index')) {
        if (!checkPermission('expenses.view')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'POST' && ($action === '' || $action === 'store')) {
        if (!checkPermission('expenses.create')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'PUT' && $id) {
        if (!checkPermission('expenses.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    }

    switch ($method) {
        case 'GET':
            if ($action === '' || $action === 'index') {
                $request = new App\Core\Http\Request($_GET);
                echo json_encode($controller->index($request));
            } elseif ($id) {
                echo json_encode($controller->show($id));
            }
            break;
        case 'POST':
            if ($action === '' || $action === 'store') {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->store($request));
            }
            break;
        case 'PUT':
            if ($action === 'status' && $id) {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->updateStatus($request, $id));
            }
            break;
    }
}

function handleGuestRequest($controller, $method, $action, $id) {
    // Permission checks
    if ($method === 'GET' && ($action === '' || $action === 'index')) {
        if (!checkPermission('guests.view')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'POST' && ($action === '' || $action === 'store')) {
        if (!checkPermission('guests.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'PUT' && $id) {
        if (!checkPermission('guests.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    } elseif ($method === 'DELETE' && $id) {
        if (!checkPermission('guests.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    }

    switch ($method) {
        case 'GET':
            if ($action === '' || $action === 'index') {
                $request = new App\Core\Http\Request($_GET);
                echo json_encode($controller->index($request));
            } elseif ($action === 'search') {
                $request = new App\Core\Http\Request($_GET);
                echo json_encode($controller->search($request));
            } elseif ($id) {
                echo json_encode($controller->show($id));
            }
            break;
        case 'POST':
            if ($action === '' || $action === 'store') {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->store($request));
            }
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->update($request, $id));
            }
            break;
        case 'DELETE':
            if ($id) {
                echo json_encode($controller->destroy($id));
            }
            break;
    }
}

function handleReportRequest($controller, $method, $action) {
    // Permission checks - all reports require reports.view permission
    if (!checkPermission('reports.view')) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
        return;
    }

    switch ($method) {
        case 'GET':
            if ($action === 'financial') {
                $request = new App\Core\Http\Request($_GET);
                echo json_encode($controller->financial($request));
            } elseif ($action === 'reservations') {
                $request = new App\Core\Http\Request($_GET);
                echo json_encode($controller->reservations($request));
            } elseif ($action === 'occupancy') {
                $request = new App\Core\Http\Request($_GET);
                echo json_encode($controller->occupancy($request));
            }
            break;
    }
}

function handleSettingRequest($controller, $method, $action, $id) {
    // Permission checks - settings require settings.manage permission
    if ($method === 'PUT' && $id) {
        if (!checkPermission('settings.manage')) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            return;
        }
    }

    switch ($method) {
        case 'GET':
            if ($action === '' || $action === 'index') {
                $request = new App\Core\Http\Request($_GET);
                echo json_encode($controller->index($request));
            } elseif ($id) {
                echo json_encode($controller->show($id));
            }
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents('php://input'), true);
                $request = new App\Core\Http\Request($data);
                echo json_encode($controller->update($request, $id));
            }
            break;
    }
}
