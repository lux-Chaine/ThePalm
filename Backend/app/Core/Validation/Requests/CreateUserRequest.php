<?php

namespace App\Core\Validation\Requests;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,manager,receptionist,housekeeping,maintenance,accountant',
            'user_type' => 'sometimes|in:staff,guest',
        ];
    }

    public function customMessages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'name.min' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق',
            'role.required' => 'الدور مطلوب',
            'role.in' => 'الدور غير صحيح',
        ];
    }
}
