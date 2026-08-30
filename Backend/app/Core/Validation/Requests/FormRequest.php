<?php

namespace App\Core\Validation\Requests;

use App\Core\Http\Request;
use App\Core\Validation\Validator;

abstract class FormRequest
{
    protected Request $request;
    protected Validator $validator;
    protected array $customMessages = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->customMessages = method_exists($this, 'customMessages') ? $this->customMessages() : [];
    }

    abstract public function rules(): array;

    public function validate(): bool
    {
        $this->validator = new Validator(
            $this->request->all(),
            $this->rules(),
            $this->customMessages
        );

        return $this->validator->validate();
    }

    public function validated(): array
    {
        if (!$this->validate()) {
            throw new \Exception('Validation failed: ' . json_encode($this->errors()));
        }

        return $this->request->all();
    }

    public function errors(): array
    {
        return $this->validator->errors();
    }

    public function hasErrors(): bool
    {
        return $this->validator->hasErrors();
    }

    public function firstError(string $field): ?string
    {
        return $this->validator->firstError($field);
    }

    public function allErrors(): array
    {
        return $this->validator->allErrors();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
