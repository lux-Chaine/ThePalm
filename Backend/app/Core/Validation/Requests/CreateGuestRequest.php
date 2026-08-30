<?php

namespace App\Core\Validation\Requests;

class CreateGuestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'email' => 'sometimes|email',
            'phone' => 'required|phone',
            'nationality' => 'sometimes|string|max:50',
            'id_number' => 'sometimes|string|max:50',
            'address' => 'sometimes|string|max:255',
            'notes' => 'sometimes|string|max:500',
        ];
    }

    public function customMessages(): array
    {
        return [
            'name.required' => 'اسم الضيف مطلوب',
            'name.min' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.phone' => 'رقم الهاتف غير صحيح',
            'email.email' => 'البريد الإلكتروني غير صحيح',
        ];
    }
}
