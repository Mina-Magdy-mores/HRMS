<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FinanceCalendarsRequest extends FormRequest
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
            'finance_yr' => 'required|integer|unique:finance_calendars|min:0|max:9999',
            'finance_yr_desc' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|integer|in:0,1',
        ];
    }
    public function messages()
    {
        return [
            'finance_yr.required' => 'سنة المالية مطلوبة',
            'finance_yr.integer' => 'سنة المالية يجب أن تكون عددًا صحيحًا',
            'finance_yr.unique' => 'سنة المالية موجودة بالفعل',
            'finance_yr.min' => 'سنة المالية يجب ان تكون عددًا صحيحًا',
            'finance_yr.max' => 'سنة المالية يجب ان تكون عددًا صحيحًا',
            'finance_yr_desc.required' => 'وصف سنة المالية مطلوب',
            'finance_yr_desc.string' => 'وصف سنة المالية يجب ان يكون نص',
            'finance_yr_desc.max' => 'وصف سنة المالية لا يزيد عن 255 حرف',
            'start_date.required' => 'تاريخ البداية مطلوب',
            'start_date.date' => 'تاريخ البداية يجب ان يكون تاريخ',
            'end_date.required' => 'تاريخ النهاية مطلوب',
            'end_date.date' => 'تاريخ النهاية يجب ان يكون تاريخ',
            'status.required' => 'الحالة مطلوبة',
            'status.integer' => 'الحالة يجب ان تكون مفعل او معطل',
            'status.in' => 'الحالة يجب ان تكون مفعل او معطل',
        ];
    }
}
