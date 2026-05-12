<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Admin_panel_settingRequest extends FormRequest
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
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'image.image' => 'الصورة يجب أن تكون صورة',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 2MB',
            'status.integer' => 'حالة الشركة يجب ان يكون رقم',
            'address.string' => 'العنوان يجب ان يكون نص',
            'phone.string' => 'رقم الهاتف يجب ان يكون نص',
            'image.required' => 'الصورة مطلوبة',
            'image.mimes' => 'الصورة يجب ان تكون jpeg,png,jpg,gif,svg',
            'created_by.required' => 'منشئ الشركة مطلوب',
            'created_by.integer' => 'منشئ الشركة يجب ان يكون رقم',
            'updated_by.required' => 'محدث الشركة مطلوب',
            'updated_by.integer' => 'محدث الشركة يجب ان يكون رقم',
            'after_minute_calculate_delay.required' => 'بعد كم عدد دقيقة نحسب تاخير حضور مطلوب',
            'after_minute_calculate_delay.numeric' => 'بعد كم عدد دقيقة نحسب تاخير حضور يجب ان يكون رقم',
            'after_minute_calculate_early_departure.required' => 'بعد كم عدد دقيقة نحسب انصراف مبكر مطلوب',
            'after_minute_calculate_early_departure.numeric' => 'بعد كم عدد دقيقة نحسب انصراف مبكر يجب ان يكون رقم',
            'after_minute_quarter_day_cut.required' => 'بعد كم عدد دقيقة من مجموع الحضور او الانصراف مبكر نخصم ربع يوم مطلوب',
            'after_minute_quarter_day_cut.numeric' => 'بعد كم عدد دقيقة من مجموع الحضور او الانصراف مبكر نخصم ربع يوم يجب ان يكون رقم',
            'after_days_half_day_cut.required' => 'بعد كم مرة تاخير او انصراف مبكر نخصم نص يوم مطلوب',
            'after_days_half_day_cut.numeric' => 'بعد كم مرة تاخير او انصراف مبكر نخصم نص يوم يجب ان يكون رقم',
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
        ];
    }
}
