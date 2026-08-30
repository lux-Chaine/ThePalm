<?php

namespace App\Core\Http;

use App\Core\Validation\Validator;

class Request
{
    protected array $data = [];
    protected ?Validator $validator = null;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function merge(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function validate(array $rules, array $customMessages = []): array
    {
        $this->validator = new Validator($this->data, $rules, $customMessages);
        
        if (!$this->validator->validate()) {
            return $this->validator->errors();
        }

        return [];
    }

    public function hasValidationErrors(): bool
    {
        return $this->validator ? $this->validator->hasErrors() : false;
    }

    public function validationErrors(): array
    {
        return $this->validator ? $this->validator->errors() : [];
    }

    public function firstValidationError(string $field): ?string
    {
        return $this->validator ? $this->validator->firstError($field) : null;
    }
}
