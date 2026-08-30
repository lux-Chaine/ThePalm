<?php

namespace App\Core\Exceptions;

class ForbiddenException extends \Exception
{
    public function __construct(string $message = "Access forbidden")
    {
        parent::__construct($message, 403);
    }

    public function toArray(): array
    {
        return [
            'success' => false,
            'error' => 'forbidden',
            'message' => $this->getMessage(),
            'status_code' => $this->getCode(),
        ];
    }
}
