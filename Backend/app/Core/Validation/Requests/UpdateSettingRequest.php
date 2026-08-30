<?php

namespace App\Core\Validation\Requests;

class UpdateSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'value' => 'required',
        ];
    }

    public function customMessages(): array
    {
        return [
            'value.required' => 'القيمة مطلوبة',
        ];
    }
}
