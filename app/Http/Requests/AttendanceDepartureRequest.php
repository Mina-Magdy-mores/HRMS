<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceDepartureRequest extends FormRequest
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
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ];
    }

    public function messages(): array
    {
        return [
            'excel_file.required' => 'يرجى رفع الملف',
            'excel_file.file' => 'يرجى رفع الملف من نوع excel فقط',
            'excel_file.mimes' => 'يرجى رفع الملف من نوع excel فقط',
        ];
    }
}
