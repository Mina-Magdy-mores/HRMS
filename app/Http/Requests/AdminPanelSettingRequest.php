<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminPanelSettingRequest extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
            'after_minute_calculate_delay' => 'sometimes|numeric|min:0',
            'after_minute_calculate_early_departure' => 'sometimes|numeric|min:0',
            'after_minute_quarter_day_cut' => 'sometimes|numeric|min:0',
            'after_days_half_day_cut' => 'sometimes|numeric|min:0',
            'after_days_allday_day_cut' => 'sometimes|numeric|min:0',
            'monthly_vacation_balance' => 'sometimes|numeric|min:0',
            'after_days_begin_vacation' => 'sometimes|numeric|min:0',
            'first_balance_begin_vacation' => 'sometimes|numeric|min:0',
            'sanctions_value_first_absence' => 'sometimes|numeric|min:0',
            'sanctions_value_second_absence' => 'sometimes|numeric|min:0',
            'sanctions_value_third_absence' => 'sometimes|numeric|min:0',
            'sanctions_value_fourth_absence' => 'sometimes|numeric|min:0',
            'after_mins_neglect' => 'sometimes|integer|min:0',
            'after_shift_max_extra_hours' => 'sometimes|integer|min:0',
            'is_allowed_to_transfer_vacation' => 'required|integer|in:0,1',
            'is_allowed_to_pull_annual_from_fingerprint' => 'required|integer|in:0,1',
            'is_allowed_to_pull_salary_variables_from_fingerprint' => 'required|integer|in:0,1',
            'is_active_system_monitoring' => 'required|integer|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'company_name.required' => 'اسم الشركة مطلوب',
            'company_name.string' => 'اسم الشركة يجب ان يكون نص',
            'status.required' => 'حالة النظام مطلوبة',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'address.required' => 'العنوان مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'image.image' => 'الصورة يجب ان تكون صورة',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 5MB',
            'status.integer' => 'حالة الشركة يجب ان يكون رقم',
            'address.string' => 'العنوان يجب ان يكون نص',
            'phone.string' => 'رقم الهاتف يجب ان يكون نص',
            'after_days_allday_day_cut.required' => 'بعد كم مرة تاخير او انصراف مبكر نخصم كل يوم مطلوب',
            'after_days_allday_day_cut.numeric' => 'بعد كم مرة تاخير او انصراف مبكر نخصم كل يوم يجب ان يكون رقم',
            'monthly_vacation_balance.required' => 'الرصيد الشهرى للاجازة مطلوب',
            'monthly_vacation_balance.numeric' => 'الرصيد الشهرى للاجازة يجب ان يكون رقم',
            'after_days_begin_vacation.required' => 'بعد كام يوم ينزل للموظف رصيد الاجازات الشهرية مطلوب',
            'after_days_begin_vacation.numeric' => 'بعد كام يوم ينزل للموظف رصيد الاجازات الشهرية يجب ان يكون رقم',
            'first_balance_begin_vacation.required' => 'رصيد الاجازات الأولي عند بدء العمل مطلوب',
            'first_balance_begin_vacation.numeric' => 'رصيد الاجازات الأولي عند بدء العمل يجب ان يكون رقم',
            'sanctions_value_first_absence.required' => 'قيمه خصم الايام بعد اول مرة غياب بدون اذن مطلوب',
            'sanctions_value_first_absence.numeric' => 'قيمه خصم الايام بعد اول مرة غياب بدون اذن يجب ان يكون رقم',
            'sanctions_value_second_absence.required' => 'قيمه خصم الايام بعد ثاني مرة غياب بدون اذن مطلوب',
            'sanctions_value_second_absence.numeric' => 'قيمه خصم الايام بعد ثاني مرة غياب بدون اذن يجب ان يكون رقم',
            'sanctions_value_third_absence.required' => 'قيمه خصم الايام بعد ثالث مرة غياب بدون اذن مطلوب',
            'sanctions_value_third_absence.numeric' => 'قيمه خصم الايام بعد ثالث مرة غياب بدون اذن يجب ان يكون رقم',
            'sanctions_value_fourth_absence.required' => 'قيمه خصم الايام بعد رابع مرة غياب بدون اذن مطلوب',
            'sanctions_value_fourth_absence.numeric' => 'قيمه خصم الايام بعد رابع مرة غياب بدون اذن يجب ان يكون رقم',
            'after_mins_neglect.required' => 'دقائق تجاهل البصمة المتكررة مطلوب',
            'after_mins_neglect.integer' => 'دقائق تجاهل البصمة المتكررة يجب ان يكون رقم صحيح',
            'after_mins_neglect.min' => 'دقائق تجاهل البصمة المتكررة يجب الا يقل عن 0',
            'after_shift_max_extra_hours.required' => 'ساعات إضافية بعد الشيفت لتقفيل البصمة مطلوب',
            'after_shift_max_extra_hours.integer' => 'ساعات إضافية بعد الشيفت لتقفيل البصمة يجب ان يكون رقم صحيح',
            'after_shift_max_extra_hours.min' => 'ساعات إضافية بعد الشيفت لتقفيل البصمة يجب الا يقل عن 0',
            'is_allowed_to_transfer_vacation.required' => 'حقل هل يسمح بترحيل رصيد الإجازات مطلوب',
            'is_allowed_to_transfer_vacation.integer' => 'حقل هل يسمح بترحيل رصيد الإجازات يجب ان يكون رقم',
            'is_allowed_to_transfer_vacation.in' => 'حقل هل يسمح بترحيل رصيد الإجازات غير صحيح',
            'is_allowed_to_pull_annual_from_fingerprint.required' => 'حقل هل يسمح بسحب الإجازات السنوية تلقائياً من البصمة مطلوب',
            'is_allowed_to_pull_annual_from_fingerprint.integer' => 'حقل هل يسمح بسحب الإجازات السنوية تلقائياً من البصمة يجب أن يكون رقم صحيح',
            'is_allowed_to_pull_annual_from_fingerprint.in' => 'حقل هل يسمح بسحب الإجازات السنوية تلقائياً من البصمة غير صحيح',
            'is_allowed_to_pull_salary_variables_from_fingerprint.required' => 'حقل هل يسمح بسحب متغيرات المرتب تلقائياً من البصمة مطلوب',
            'is_allowed_to_pull_salary_variables_from_fingerprint.integer' => 'حقل هل يسمح بسحب متغيرات المرتب تلقائياً من البصمة يجب أن يكون رقم صحيح',
            'is_allowed_to_pull_salary_variables_from_fingerprint.in' => 'حقل هل يسمح بسحب متغيرات المرتب تلقائياً من البصمة غير صحيح',
            'is_active_system_monitoring.required' => 'حقل حالة مراقب النظام مطلوب',
            'is_active_system_monitoring.integer' => 'حقل حالة مراقب النظام يجب ان يكون رقم',
            'is_active_system_monitoring.in' => 'حقل حالة مراقب النظام غير صحيح',
        ];
    }
}
