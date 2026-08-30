<?php

namespace App\Core\Exceptions;

class NotFoundException extends \Exception
{
    private string $resource;
    private ?int $id;

    public function __construct(string $resource, ?int $id = null, string $message = null)
    {
        $this->resource = $resource;
        $this->id = $id;
        
        if ($message === null) {
            $message = $id 
                ? "{$resource} with ID {$id} not found"
                : "{$resource} not found";
        }
        
        parent::__construct($message, 404);
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'success' => false,
            'error' => 'not_found',
            'message' => $this->getMessage(),
            'resource' => $this->resource,
            'id' => $this->id,
            'status_code' => $this->getCode(),
        ];
    }
}
