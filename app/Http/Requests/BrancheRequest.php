<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BrancheRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
            'status' => 'integer|in:0,1',
        ];
    }
    //ألأرسائل بالعربيه
    public function messages()
    {
        return [
            'name.required' => 'اسم الفرع مطلوب',
            'name.string' => 'اسم الفرع يجب ان يكون نص',
            'name.max' => 'اسم الفرع لا يزيد عن 255 حرف',
            'address.required' => 'العنوان مطلوب',
            'address.string' => 'العنوان يجب ان يكون نص',
            'address.max' => 'العنوان لا يزيد عن 255 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.string' => 'رقم الهاتف يجب ان يكون نص',
            'phone.max' => 'رقم الهاتف لا يزيد عن 20 حرف',
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.string' => 'البريد الالكتروني يجب ان يكون نص',
            'email.email' => 'البريد الالكتروني غير صحيح',
            'email.max' => 'البريد الالكتروني لا يزيد عن 255 حرف',
            'status.required' => 'حالة الفرع مطلوب',
            'status.in' => 'حالة الفرع يجب ان تكون مفعل او معطل',
        ];
    }
}
