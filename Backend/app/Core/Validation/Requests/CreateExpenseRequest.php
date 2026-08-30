<?php

namespace App\Core\Validation\Requests;

class CreateExpenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'created_by' => 'required|integer',
            'expense_date' => 'sometimes|date',
            'status' => 'sometimes|in:pending,paid,cancelled',
        ];
    }

    public function customMessages(): array
    {
        return [
            'category.required' => 'الفئة مطلوبة',
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقماً',
            'amount.min' => 'المبلغ لا يمكن أن يكون سالباً',
            'description.required' => 'الوصف مطلوب',
            'status.in' => 'الحالة غير صحيحة',
        ];
    }
}
