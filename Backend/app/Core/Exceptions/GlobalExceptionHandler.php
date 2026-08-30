<?php

namespace App\Core\Exceptions;

use Throwable;

class GlobalExceptionHandler
{
    /**
     * Handle exception and return formatted response
     */
    public static function handle(Throwable $exception): array
    {
        // Handle custom exceptions
        if ($exception instanceof ValidationException) {
            return $exception->toArray();
        }

        if ($exception instanceof NotFoundException) {
            return $exception->toArray();
        }

        if ($exception instanceof UnauthorizedException) {
            return $exception->toArray();
        }

        if ($exception instanceof ForbiddenException) {
            return $exception->toArray();
        }

        if ($exception instanceof BadRequestException) {
            return $exception->toArray();
        }

        if ($exception instanceof BusinessRuleException) {
            return $exception->toArray();
        }

        if ($exception instanceof ConflictException) {
            return $exception->toArray();
        }

        // Handle general exceptions
        return self::handleGeneralException($exception);
    }

    /**
     * Handle general/unknown exceptions
     */
    private static function handleGeneralException(Throwable $exception): array
    {
        $statusCode = 500;
        $errorType = 'internal_server_error';
        $message = 'An unexpected error occurred';

        // In development, show more details
        if (self::isDevelopment()) {
            $message = $exception->getMessage();
        }

        return [
            'success' => false,
            'error' => $errorType,
            'message' => $message,
            'status_code' => $statusCode,
            'trace' => self::isDevelopment() ? self::formatTrace($exception) : null,
        ];
    }

    /**
     * Check if application is in development mode
     */
    private static function isDevelopment(): bool
    {
        return ($_ENV['APP_ENV'] ?? 'production') === 'development';
    }

    /**
     * Format exception trace for debugging
     */
    private static function formatTrace(Throwable $exception): array
    {
        return [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ];
    }

    /**
     * Log exception
     */
    public static function log(Throwable $exception): void
    {
        $logMessage = sprintf(
            "[%s] %s in %s:%d\n%s",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );

        error_log($logMessage, 3, __DIR__ . '/../../storage/logs/error.log');
    }

    /**
     * Handle exception with logging and response
     */
    public static function handleWithLogging(Throwable $exception): array
    {
        self::log($exception);
        return self::handle($exception);
    }
}
