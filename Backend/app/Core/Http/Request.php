<?php

namespace App\Core\Http;

class Request
{
    protected array $data = [];

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

    public function validate(array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $ruleArray = is_array($rule) ? $rule : explode('|', $rule);
            
            foreach ($ruleArray as $r) {
                if ($r === 'required' && !isset($this->data[$field])) {
                    $errors[$field][] = "The $field field is required.";
                }
                if ($r === 'email' && isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "The $field must be a valid email address.";
                }
                if (str_starts_with($r, 'min:') && isset($this->data[$field])) {
                    $min = (int) substr($r, 4);
                    if (strlen($this->data[$field]) < $min) {
                        $errors[$field][] = "The $field must be at least $min characters.";
                    }
                }
            }
        }
        
        return $errors;
    }
}
