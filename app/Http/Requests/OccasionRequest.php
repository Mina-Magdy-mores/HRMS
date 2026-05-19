<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OccasionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days_count' => 'required|numeric|min:0',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المناسبة مطلوب',
            'from_date.required' => 'تاريخ البداية مطلوب',
            'from_date.date' => 'تاريخ البداية يجب أن يكون تاريخ صالح',
            'to_date.required' => 'تاريخ النهاية مطلوب',
            'to_date.date' => 'تاريخ النهاية يجب أن يكون تاريخ صالح',
            'to_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',
            'days_count.required' => 'عدد الأيام مطلوب',
            'days_count.numeric' => 'عدد الأيام يجب أن يكون رقمًا',
            'days_count.min' => 'عدد الأيام لا يمكن أن يكون أقل من صفر',
            'status.integer' => 'الحالة يجب أن تكون مفعل او معطل',
            'status.in' => 'الحالة يجب أن تكون مفعل او معطل',
        ];
    }
}
