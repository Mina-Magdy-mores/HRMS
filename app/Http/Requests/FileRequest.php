<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png,json|max:10240'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'حقل الاسم مطلوب',
            'name.string' => 'حقل الاسم يجب أن يكون نصاً',
            'name.max' => 'حقل الاسم لا يمكن أن يتجاوز 255 حرف',
            'file.required' => 'حقل الملف مطلوب',
            'file.file' => 'الملف يجب أن يكون ملفاً واحداً',
            'file.mimes' => 'الملف يجب أن يكون من نوع pdf, doc, docx, jpg, png أو json',
            'file.max' => 'حجم الملف لا يمكن أن يتجاوز 10240 كيلوبايت'
        ];
    }
}
