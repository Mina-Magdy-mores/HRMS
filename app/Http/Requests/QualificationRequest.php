<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QualificationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المؤهل مطلوب',
            'name.string' => 'اسم المؤهل يجب ان يكون نص',
            'name.max' => 'اسم المؤهل لا يمكن أن يتجاوز 255 حرف',
            'status.required' => 'الحالة مطلوبة',
            'status.integer' => 'الحالة يجب ان تكون مفعل او معطل',
            'status.in' => 'الحالة يجب ان تكون مفعل او معطل',
        ];
    }
}
