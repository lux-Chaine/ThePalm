<?php

namespace App\Core\Exceptions;

class BadRequestException extends \Exception
{
    public function __construct(string $message = "Bad request")
    {
        parent::__construct($message, 400);
    }

    public function toArray(): array
    {
        return [
            'success' => false,
            'error' => 'bad_request',
            'message' => $this->getMessage(),
            'status_code' => $this->getCode(),
        ];
    }
}
