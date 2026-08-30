<?php

namespace App\Core\Exceptions;

class UnauthorizedException extends \Exception
{
    public function __construct(string $message = "Unauthorized access")
    {
        parent::__construct($message, 401);
    }

    public function toArray(): array
    {
        return [
            'success' => false,
            'error' => 'unauthorized',
            'message' => $this->getMessage(),
            'status_code' => $this->getCode(),
        ];
    }
}
