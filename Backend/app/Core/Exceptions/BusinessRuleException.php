<?php

namespace App\Core\Exceptions;

class BusinessRuleException extends \Exception
{
    private ?string $rule;

    public function __construct(string $message, ?string $rule = null)
    {
        $this->rule = $rule;
        parent::__construct($message, 422);
    }

    public function getRule(): ?string
    {
        return $this->rule;
    }

    public function toArray(): array
    {
        return [
            'success' => false,
            'error' => 'business_rule_violation',
            'message' => $this->getMessage(),
            'rule' => $this->rule,
            'status_code' => $this->getCode(),
        ];
    }
}
