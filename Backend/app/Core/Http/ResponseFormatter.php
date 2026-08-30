<?php

namespace App\Core\Http;

class ResponseFormatter
{
    /**
     * Format successful response
     */
    public static function success($data = null, string $message = 'Success', int $statusCode = 200): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status_code' => $statusCode,
        ];
    }

    /**
     * Format error response
     */
    public static function error(string $message, string $errorType = 'error', int $statusCode = 400, array $errors = null): array
    {
        $response = [
            'success' => false,
            'error' => $errorType,
            'message' => $message,
            'status_code' => $statusCode,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return $response;
    }

    /**
     * Format validation error response
     */
    public static function validationError(array $errors, string $message = 'Validation failed'): array
    {
        return self::error($message, 'validation_error', 422, $errors);
    }

    /**
     * Format not found response
     */
    public static function notFound(string $resource = 'Resource', ?int $id = null): array
    {
        $message = $id ? "{$resource} with ID {$id} not found" : "{$resource} not found";
        return self::error($message, 'not_found', 404);
    }

    /**
     * Format unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized access'): array
    {
        return self::error($message, 'unauthorized', 401);
    }

    /**
     * Format forbidden response
     */
    public static function forbidden(string $message = 'Access forbidden'): array
    {
        return self::error($message, 'forbidden', 403);
    }

    /**
     * Format conflict response
     */
    public static function conflict(string $message = 'Resource conflict'): array
    {
        return self::error($message, 'conflict', 409);
    }

    /**
     * Format created response
     */
    public static function created($data = null, string $message = 'Resource created successfully'): array
    {
        return self::success($data, $message, 201);
    }

    /**
     * Format updated response
     */
    public static function updated($data = null, string $message = 'Resource updated successfully'): array
    {
        return self::success($data, $message, 200);
    }

    /**
     * Format deleted response
     */
    public static function deleted(string $message = 'Resource deleted successfully'): array
    {
        return self::success(null, $message, 200);
    }

    /**
     * Format paginated response
     */
    public static function paginate(array $data, int $total, int $page, int $perPage, string $message = 'Success'): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => (int) ceil($total / $perPage),
                'from' => (($page - 1) * $perPage) + 1,
                'to' => min($page * $perPage, $total),
            ],
            'status_code' => 200,
        ];
    }

    /**
     * Format collection response
     */
    public static function collection(array $data, string $message = 'Success'): array
    {
        return self::success($data, $message, 200);
    }

    /**
     * Format single item response
     */
    public static function item($data, string $message = 'Success'): array
    {
        return self::success($data, $message, 200);
    }

    /**
     * Send JSON response with proper headers
     */
    public static function send(array $response): void
    {
        http_response_code($response['status_code'] ?? 200);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    /**
     * Send success response
     */
    public static function sendSuccess($data = null, string $message = 'Success', int $statusCode = 200): void
    {
        self::send(self::success($data, $message, $statusCode));
    }

    /**
     * Send error response
     */
    public static function sendError(string $message, string $errorType = 'error', int $statusCode = 400, array $errors = null): void
    {
        self::send(self::error($message, $errorType, $statusCode, $errors));
    }
}
