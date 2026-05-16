<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            'name' => 'required|string',
            'number' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|integer|in:0,1',
        ];
    }
    //in arabic
    public function messages()
    {
        return [
            'name.required' => 'اسم القسم مطلوب',
            'name.string' => 'اسم القسم يجب ان يكون نص',
            'number.required' => 'رقم القسم مطلوب',
            'number.string' => 'رقم القسم يجب ان يكون نص',
            'description.required' => 'وصف القسم مطلوب',
            'description.string' => 'وصف القسم يجب ان يكون نص',
            'status.required' => 'حالة القسم مطلوبة',
            'status.integer' => 'حالة القسم يجب ان تكون مفعل او معطل',
            'status.in' => 'حالة القسم يجب ان تكون مفعل او معطل',
        ];
    }
}
