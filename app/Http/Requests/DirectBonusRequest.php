<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DirectBonusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'finance_monthly_calendar_id' => 'required|exists:finance_monthly_calendars,id',
            'bonus_id' => 'required|exists:bonuses,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'الموظف مطلوب',
            'employee_id.exists' => 'الموظف المحدد غير موجود',
            'finance_monthly_calendar_id.required' => 'الشهر المالي مطلوب',
            'finance_monthly_calendar_id.exists' => 'الشهر المالي المحدد غير موجود',
            'bonus_id.required' => 'نوع المكافأة مطلوب',
            'bonus_id.exists' => 'نوع المكافأة المحدد غير موجود',
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقماً',
            'amount.min' => 'المبلغ يجب ألا يقل عن 0.01',
            'payment_date.required' => 'تاريخ الصرف مطلوب',
            'payment_date.date' => 'تاريخ الصرف غير صالح',
            'status.required' => 'الحالة مطلوبة',
            'status.in' => 'الحالة يجب أن تكون مفعل أو معطل',
        ];
    }
}
