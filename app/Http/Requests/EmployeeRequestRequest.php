<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequestRequest extends FormRequest
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
            'employee_request_type_id' => 'required|exists:employee_request_types,id',
            'title'                    => 'required|string|max:255',
            'content'                  => 'required|string|max:5000',
        ];
    }

    /**
     * Get the validation messages.
     */
    public function messages(): array
    {
        return [
            'employee_request_type_id.required' => 'نوع الطلب مطلوب',
            'employee_request_type_id.exists'   => 'نوع الطلب المختار غير صحيح',
            'title.required'                    => 'عنوان الطلب مطلوب',
            'title.string'                      => 'عنوان الطلب يجب أن يكون نصاً',
            'title.max'                         => 'عنوان الطلب لا يجب أن يتجاوز 255 حرفاً',
            'content.required'                  => 'محتوى الطلب مطلوب',
            'content.string'                    => 'محتوى الطلب يجب أن يكون نصاً',
            'content.max'                       => 'محتوى الطلب لا يجب أن يتجاوز 5000 حرفاً',
        ];
    }
}
