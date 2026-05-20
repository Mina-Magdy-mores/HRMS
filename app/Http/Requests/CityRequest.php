<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'governorate_id' => 'required|integer|exists:governorates,id',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المدينة مطلوب',
            'name.string' => 'اسم المدينة يجب أن يكون نصاً',
            'name.max' => 'اسم المدينة لا يمكن أن يتجاوز 255 حرف',
            'governorate_id.required' => 'المحافظة مطلوبة',
            'governorate_id.integer' => 'المحافظة يجب أن تكون رقماً',
            'governorate_id.exists' => 'المحافظة المختارة غير موجودة',
            'status.required' => 'الحالة مطلوبة',
            'status.integer' => 'الحالة يجب أن تكون رقماً',
            'status.in' => 'الحالة يجب أن تكون مفعل او معطل',
        ];
    }
}
