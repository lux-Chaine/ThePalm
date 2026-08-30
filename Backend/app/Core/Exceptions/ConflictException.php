<?php

namespace App\Core\Exceptions;

class ConflictException extends \Exception
{
    public function __construct(string $message = "Resource conflict")
    {
        parent::__construct($message, 409);
    }

    public function toArray(): array
    {
        return [
            'success' => false,
            'error' => 'conflict',
            'message' => $this->getMessage(),
            'status_code' => $this->getCode(),
        ];
    }
}
