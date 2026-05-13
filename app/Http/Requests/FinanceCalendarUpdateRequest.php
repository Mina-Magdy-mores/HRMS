<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinanceCalendarUpdateRequest extends FormRequest
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
            'finance_yr' => [
                'sometimes',
                'integer',
                'min:0',
                'max:9999',
                Rule::unique('finance_calendars', 'finance_yr')
                    ->ignore($this->route('financeCalendar')),
            ],
            'finance_yr_desc' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'status' => 'sometimes|integer|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'finance_yr.integer' => 'سنة المالية يجب أن تكون عددًا صحيحًا',
            'finance_yr.unique' => 'سنة المالية موجودة بالفعل',
            'finance_yr.min' => 'سنة المالية يجب ان تكون عددًا صحيحًا',
            'finance_yr.max' => 'سنة المالية يجب ان تكون عددًا صحيحًا',
            'finance_yr_desc.string' => 'وصف سنة المالية يجب ان يكون نص',
            'finance_yr_desc.max' => 'وصف سنة المالية لا يزيد عن 255 حرف',
            'start_date.date' => 'تاريخ البداية يجب ان يكون تاريخ',
            'end_date.date' => 'تاريخ النهاية يجب ان يكون تاريخ',
            'status.integer' => 'الحالة يجب ان تكون مفعل او معطل',
            'status.in' => 'الحالة يجب ان تكون مفعل او معطل',
        ];
    }
}
