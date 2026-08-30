<?php

namespace App\Modules\Settings\Domain;

class Setting
{
    public function __construct(
        public ?int $id = null,
        public string $key,
        public string $value,
        public string $type = 'string', // string, number, boolean, json
        public string $category = 'general', // general, hotel, pricing, booking, billing
        public ?string $description = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'value' => $this->getTypedValue(),
            'type' => $this->type,
            'category' => $this->category,
            'description' => $this->description
        ];
    }

    private function getTypedValue(): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($this->value) ? (strpos($this->value, '.') !== false ? (float) $this->value : (int) $this->value) : $this->value,
            'json' => json_decode($this->value, true) ?? $this->value,
            default => $this->value
        };
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            key: $data['key'],
            value: $data['value'],
            type: $data['type'] ?? 'string',
            category: $data['category'] ?? 'general',
            description: $data['description'] ?? null
        );
    }
}
