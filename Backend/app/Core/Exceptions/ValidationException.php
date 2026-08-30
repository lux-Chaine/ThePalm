<?php

namespace App\Core\Exceptions;

class ValidationException extends \Exception
{
    private array $errors;

    public function __construct(array $errors, string $message = "Validation failed")
    {
        parent::__construct($message, 422);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function toArray(): array
    {
        return [
            'success' => false,
            'error' => 'validation_error',
            'message' => $this->getMessage(),
            'errors' => $this->errors,
            'status_code' => $this->getCode(),
        ];
    }
}
