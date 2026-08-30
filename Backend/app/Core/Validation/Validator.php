<?php

namespace App\Core\Validation;

class Validator
{
    protected array $data = [];
    protected array $rules = [];
    protected array $errors = [];
    protected array $customMessages = [];
    protected string $locale = 'en';

    public function __construct(array $data, array $rules, array $customMessages = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->customMessages = $customMessages;
    }

    public function validate(): bool
    {
        $this->errors = [];

        foreach ($this->rules as $field => $ruleString) {
            $rules = is_array($ruleString) ? $ruleString : explode('|', $ruleString);
            
            foreach ($rules as $rule) {
                $this->validateField($field, $rule);
            }
        }

        return empty($this->errors);
    }

    protected function validateField(string $field, string $rule): void
    {
        $value = $this->data[$field] ?? null;
        $ruleName = $this->extractRuleName($rule);
        $ruleParameters = $this->extractRuleParameters($rule);

        $method = 'validate' . ucfirst($ruleName);

        if (method_exists($this, $method)) {
            $this->$method($field, $value, $ruleParameters);
        } elseif (is_callable($rule)) {
            $rule($field, $value, $this->errors);
        }
    }

    protected function extractRuleName(string $rule): string
    {
        return strstr($rule, ':', true) ?: $rule;
    }

    protected function extractRuleParameters(string $rule): array
    {
        if (strpos($rule, ':') === false) {
            return [];
        }
        return explode(',', substr($rule, strpos($rule, ':') + 1));
    }

    protected function addError(string $field, string $rule, array $parameters = []): void
    {
        $message = $this->getErrorMessage($field, $rule, $parameters);
        $this->errors[$field][] = $message;
    }

    protected function getErrorMessage(string $field, string $rule, array $parameters = []): string
    {
        $key = "{$field}.{$rule}";
        
        if (isset($this->customMessages[$key])) {
            return $this->replacePlaceholders($this->customMessages[$key], $field, $parameters);
        }

        if (isset($this->customMessages[$rule])) {
            return $this->replacePlaceholders($this->customMessages[$rule], $field, $parameters);
        }

        return $this->getDefaultMessage($field, $rule, $parameters);
    }

    protected function replacePlaceholders(string $message, string $field, array $parameters): string
    {
        $message = str_replace(':field', $field, $message);
        
        foreach ($parameters as $index => $value) {
            $message = str_replace(":param{$index}", $value, $message);
        }

        return $message;
    }

    protected function getDefaultMessage(string $field, string $rule, array $parameters): string
    {
        $messages = [
            'required' => "The {$field} field is required.",
            'email' => "The {$field} must be a valid email address.",
            'min' => "The {$field} must be at least {$parameters[0]} characters.",
            'max' => "The {$field} must not exceed {$parameters[0]} characters.",
            'numeric' => "The {$field} must be a number.",
            'integer' => "The {$field} must be an integer.",
            'string' => "The {$field} must be a string.",
            'date' => "The {$field} must be a valid date.",
            'url' => "The {$field} must be a valid URL.",
            'phone' => "The {$field} must be a valid phone number.",
            'confirmed' => "The {$field} confirmation does not match.",
            'regex' => "The {$field} format is invalid.",
            'between' => "The {$field} must be between {$parameters[0]} and {$parameters[1]}.",
            'in' => "The {$field} must be one of: " . implode(', ', $parameters) . ".",
            'array' => "The {$field} must be an array.",
            'boolean' => "The {$field} must be true or false.",
        ];

        return $messages[$rule] ?? "The {$field} is invalid.";
    }

    // Validation Rules

    protected function validateRequired(string $field, $value): void
    {
        if (is_null($value) || $value === '') {
            $this->addError($field, 'required');
        }
    }

    protected function validateEmail(string $field, $value): void
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'email');
        }
    }

    protected function validateMin(string $field, $value, array $parameters): void
    {
        if (!empty($value)) {
            $min = (int) $parameters[0];
            if (is_string($value) && strlen($value) < $min) {
                $this->addError($field, 'min', $parameters);
            } elseif (is_numeric($value) && $value < $min) {
                $this->addError($field, 'min', $parameters);
            } elseif (is_array($value) && count($value) < $min) {
                $this->addError($field, 'min', $parameters);
            }
        }
    }

    protected function validateMax(string $field, $value, array $parameters): void
    {
        if (!empty($value)) {
            $max = (int) $parameters[0];
            if (is_string($value) && strlen($value) > $max) {
                $this->addError($field, 'max', $parameters);
            } elseif (is_numeric($value) && $value > $max) {
                $this->addError($field, 'max', $parameters);
            } elseif (is_array($value) && count($value) > $max) {
                $this->addError($field, 'max', $parameters);
            }
        }
    }

    protected function validateNumeric(string $field, $value): void
    {
        if (!empty($value) && !is_numeric($value)) {
            $this->addError($field, 'numeric');
        }
    }

    protected function validateInteger(string $field, $value): void
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
            $this->addError($field, 'integer');
        }
    }

    protected function validateString(string $field, $value): void
    {
        if (!empty($value) && !is_string($value)) {
            $this->addError($field, 'string');
        }
    }

    protected function validateDate(string $field, $value): void
    {
        if (!empty($value)) {
            if (is_string($value)) {
                $date = date_create_from_format('Y-m-d', $value);
                if (!$date || $date->format('Y-m-d') !== $value) {
                    $this->addError($field, 'date');
                }
            } elseif (!($value instanceof \DateTime)) {
                $this->addError($field, 'date');
            }
        }
    }

    protected function validateUrl(string $field, $value): void
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, 'url');
        }
    }

    protected function validatePhone(string $field, $value): void
    {
        if (!empty($value)) {
            // Basic phone validation - adjust regex as needed
            $pattern = '/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/';
            if (!preg_match($pattern, $value)) {
                $this->addError($field, 'phone');
            }
        }
    }

    protected function validateConfirmed(string $field, $value): void
    {
        $confirmationField = $field . '_confirmation';
        if (isset($this->data[$confirmationField]) && $value !== $this->data[$confirmationField]) {
            $this->addError($field, 'confirmed');
        }
    }

    protected function validateRegex(string $field, $value, array $parameters): void
    {
        if (!empty($value) && !preg_match($parameters[0], $value)) {
            $this->addError($field, 'regex', $parameters);
        }
    }

    protected function validateBetween(string $field, $value, array $parameters): void
    {
        if (!empty($value)) {
            $min = (float) $parameters[0];
            $max = (float) $parameters[1];
            
            if (is_numeric($value) && ($value < $min || $value > $max)) {
                $this->addError($field, 'between', $parameters);
            } elseif (is_string($value) && (strlen($value) < $min || strlen($value) > $max)) {
                $this->addError($field, 'between', $parameters);
            }
        }
    }

    protected function validateIn(string $field, $value, array $parameters): void
    {
        if (!empty($value) && !in_array($value, $parameters)) {
            $this->addError($field, 'in', $parameters);
        }
    }

    protected function validateArray(string $field, $value): void
    {
        if (!empty($value) && !is_array($value)) {
            $this->addError($field, 'array');
        }
    }

    protected function validateBoolean(string $field, $value): void
    {
        if (!empty($value) && !in_array($value, [true, false, 1, 0, '1', '0', 'true', 'false'], true)) {
            $this->addError($field, 'boolean');
        }
    }

    // Custom Rule Support

    public function addCustomRule(string $name, callable $callback): void
    {
        $this->customRules[$name] = $callback;
    }

    // Getters

    public function errors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function firstError(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }

    public function allErrors(): array
    {
        $allErrors = [];
        foreach ($this->errors as $field => $errors) {
            $allErrors[$field] = $errors[0];
        }
        return $allErrors;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }
}
