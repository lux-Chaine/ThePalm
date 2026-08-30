<?php

namespace App\Core\Validation\Requests;

class CreateInvoiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reservation_id' => 'required|integer',
            'created_by' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'discount_amount' => 'sometimes|numeric|min:0',
            'tax_amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer',
            'due_date' => 'sometimes|date',
            'notes' => 'sometimes|string|max:500',
        ];
    }

    public function customMessages(): array
    {
        return [
            'reservation_id.required' => 'معرف الحجز مطلوب',
            'created_by.required' => 'معرف منشئ الفاتورة مطلوب',
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقماً',
            'amount.min' => 'المبلغ لا يمكن أن يكون سالباً',
            'payment_method.required' => 'طريقة الدفع مطلوبة',
            'payment_method.in' => 'طريقة الدفع غير صحيحة',
        ];
    }
}
