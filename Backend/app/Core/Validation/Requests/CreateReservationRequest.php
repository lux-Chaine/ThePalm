<?php

namespace App\Core\Validation\Requests;

class CreateReservationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'guest_id' => 'required|integer',
            'room_id' => 'required|integer',
            'user_id' => 'required|integer',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date',
            'number_of_guests' => 'required|integer|min:1|max:10',
            'special_requests' => 'sometimes|string|max:500',
        ];
    }

    public function customMessages(): array
    {
        return [
            'guest_id.required' => 'معرف الضيف مطلوب',
            'room_id.required' => 'معرف الغرفة مطلوب',
            'user_id.required' => 'معرف المستخدم مطلوب',
            'check_in_date.required' => 'تاريخ الوصول مطلوب',
            'check_in_date.date' => 'تاريخ الوصول غير صحيح',
            'check_out_date.required' => 'تاريخ المغادرة مطلوب',
            'check_out_date.date' => 'تاريخ المغادرة غير صحيح',
            'number_of_guests.required' => 'عدد الضيوف مطلوب',
            'number_of_guests.min' => 'عدد الضيوف يجب أن يكون 1 على الأقل',
            'number_of_guests.max' => 'عدد الضيوف لا يمكن أن يتجاوز 10',
        ];
    }
}
