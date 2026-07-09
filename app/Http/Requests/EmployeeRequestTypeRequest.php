<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequestTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'is_active' => 'required|integer|in:0,1',
        ];
    }

    /**
     * Get the validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'اسم نوع الطلب مطلوب',
            'name.string'        => 'اسم نوع الطلب يجب أن يكون نصاً',
            'name.max'           => 'اسم نوع الطلب لا يجب أن يتجاوز 255 حرفاً',
            'is_active.required' => 'حالة التفعيل مطلوبة',
            'is_active.in'       => 'حالة التفعيل غير صحيحة',
        ];
    }
}
