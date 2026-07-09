<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeTaskRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'employee_id'  => 'required|integer|exists:employees,id',
            'is_completed' => 'required|integer|in:0,1,2',
            'notes'        => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required'        => 'عنوان المهمة مطلوب',
            'title.string'          => 'عنوان المهمة يجب أن يكون نصاً',
            'title.max'             => 'عنوان المهمة لا يجب أن يتجاوز 255 حرفاً',
            'content.required'      => 'محتوى المهمة مطلوب',
            'content.string'        => 'محتوى المهمة يجب أن يكون نصاً',
            'employee_id.required'  => 'الموظف المرتبط مطلوب',
            'employee_id.integer'   => 'الموظف المختار غير صحيح',
            'employee_id.exists'    => 'الموظف المختار غير موجود بسجلاتنا',
            'is_completed.required' => 'حالة المهمة مطلوبة',
            'is_completed.in'       => 'حالة المهمة المحددة غير صحيحة',
            'notes.max'             => 'ملاحظات المهمة لا يجب أن تتجاوز 1000 حرفاً',
        ];
    }
}
