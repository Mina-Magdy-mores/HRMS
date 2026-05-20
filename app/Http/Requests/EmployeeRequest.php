<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_code' => 'required|integer|unique:employees,employee_code',
            'fingerprint_code' => 'required|string|max:255|unique:employees,fingerprint_code',
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'nullable|email|max:255|unique:employees,email',
            'home_telephone' => 'nullable|string|max:50',
            'work_telephone' => 'nullable|string|max:50',
            'blood_group_id' => 'nullable|exists:blood_groups,id',
            'stable_address' => 'nullable|string|max:500',
            'country_id' => 'nullable|exists:countries,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'city_id' => 'nullable|exists:cities,id',
            'children_count' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:1,2,3',
            'marital_status' => 'nullable|in:1,2,3,4,5',
            'military_status' => 'nullable|in:1,2,3',
            'military_start_date' => 'nullable|date',
            'military_end_date' => 'nullable|date',
            'military_weapon' => 'nullable|string|max:255',
            'military_exemption_date' => 'nullable|date',
            'military_exemption_reason' => 'nullable|string|max:500',
            'driving_license' => 'required|in:0,1',
            'driving_license_number' => 'nullable|string|max:255',
            'religion_id' => 'nullable|exists:religions,id',
            'qualifications_id' => 'nullable|exists:qualifications,id',
            'qualification_year' => 'nullable|string|max:50',
            'graduation_grade' => 'nullable|in:1,2,3,4,5',
            'graduation_specialization' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'hire_date_day_month_year' => 'nullable|date',
            'employment_status' => 'required|in:0,1',
            'resignation_id' => 'nullable|exists:resignations,id',
            'resignation_date' => 'nullable|date',
            'resignation_reason' => 'nullable|string|max:500',
            'motivation_type' => 'nullable|in:0,1,2',
            'motivation_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:1,2,3',
            'bank_account_number' => 'nullable|string|max:255',
            'has_disability' => 'nullable|in:0,1',
            'disability_description' => 'nullable|string|max:500',
            'has_relative' => 'nullable|in:0,1',
            'relative_description' => 'nullable|string|max:500',
            'urgent_contact_details' => 'nullable|string|max:1000',
            'daily_work_hours' => 'nullable|numeric|min:0',
            'job_id' => 'required|exists:jobs_categories,id',
            'department_id' => 'required|exists:departments,id',
            'nationality_id' => 'required|exists:nationalities,id',
            'nationality_number' => 'nullable|string|max:255',
            'nationality_expiry_date' => 'nullable|date',
            'nationality_place_of_issue' => 'nullable|string|max:255',
            'sponsor_name' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'passport_expiry_date' => 'nullable|date',
            'passport_place_of_issue' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'salary' => 'nullable|numeric|min:0',
            'lang_id' => 'nullable|integer',
            'company_id' => 'required|integer',
            'added_by' => 'required|integer|exists:admins,id',
            'updated_by' => 'nullable|integer|exists:admins,id',
            'fixed_shift' => 'nullable|in:0,1',
            'shift_type_id' => 'nullable|exists:shifts_types,id',
            'payment_per_day' => 'nullable|numeric|min:0',
            'has_social_insurance' => 'nullable|in:0,1',
            'social_insurance_amount' => 'nullable|numeric|min:0',
            'social_insurance_number' => 'nullable|string|max:255',
            'has_medical_insurance' => 'nullable|in:0,1',
            'medical_insurance_amount' => 'nullable|numeric|min:0',
            'fixed_allowance' => 'nullable|in:0,1',
            'has_attendance' => 'nullable|in:0,1',
            'vacation_formula' => 'nullable|integer',
            'active_for_vacation' => 'nullable|in:0,1',
            'has_sensitive_data' => 'nullable|in:0,1',
            'branch_id' => 'required|exists:branches,id',
        ];
    }
    public function messages()
    {
        return [
            'employee_code.required' => 'كود الموظف مطلوب',
            'employee_code.integer' => 'كود الموظف يجب أن يكون رقم صحيح',
            'employee_code.unique' => 'كود الموظف مستخدم من قبل',

            'fingerprint_code.required' => 'كود البصمة مطلوب',
            'fingerprint_code.string' => 'كود البصمة يجب أن يكون نص',
            'fingerprint_code.max' => 'كود البصمة لا يجب أن يتجاوز 255 حرف',
            'fingerprint_code.unique' => 'كود البصمة مستخدم من قبل',

            'name.required' => 'اسم الموظف مطلوب',
            'name.string' => 'الاسم يجب أن يكون نص',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف',

            'birth_date.date' => 'تاريخ الميلاد يجب أن يكون تاريخ صحيح',

            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',

            'home_telephone.string' => 'رقم الهاتف المنزلي يجب أن يكون نص',
            'home_telephone.max' => 'رقم الهاتف المنزلي لا يجب أن يتجاوز 50 حرف',

            'work_telephone.string' => 'رقم الهاتف الوظيفي يجب أن يكون نص',
            'work_telephone.max' => 'رقم الهاتف الوظيفي لا يجب أن يتجاوز 50 حرف',

            'blood_group_id.exists' => 'فصيلة الدم المختارة غير موجودة',

            'stable_address.string' => 'العنوان الثابت يجب أن يكون نص',
            'stable_address.max' => 'العنوان الثابت لا يجب أن يتجاوز 500 حرف',

            'country_id.exists' => 'الدولة المختارة غير موجودة',
            'governorate_id.exists' => 'المحافظة المختارة غير موجودة',
            'city_id.exists' => 'المدينة المختارة غير موجودة',

            'children_count.integer' => 'عدد الأولاد يجب أن يكون رقم',
            'children_count.min' => 'عدد الأولاد لا يمكن أن يكون أقل من 0',

            'gender.in' => 'النوع المختار غير صحيح',
            'marital_status.in' => 'الحالة الاجتماعية المختارة غير صحيحة',
            'military_status.in' => 'الحالة العسكرية المختارة غير صحيحة',

            'military_start_date.date' => 'تاريخ بداية الخدمة العسكرية يجب أن يكون تاريخ صحيح',
            'military_end_date.date' => 'تاريخ نهاية الخدمة العسكرية يجب أن يكون تاريخ صحيح',
            'military_weapon.string' => 'نوع السلاح يجب أن يكون نص',
            'military_weapon.max' => 'نوع السلاح لا يجب أن يتجاوز 255 حرف',
            'military_exemption_date.date' => 'تاريخ الإعفاء العسكري يجب أن يكون تاريخ صحيح',
            'military_exemption_reason.string' => 'سبب الإعفاء العسكري يجب أن يكون نص',
            'military_exemption_reason.max' => 'سبب الإعفاء العسكري لا يجب أن يتجاوز 500 حرف',

            'driving_license.required' => 'حقل رخصة القيادة مطلوب',
            'driving_license.in' => 'قيمة رخصة القيادة غير صحيحة',

            'driving_license_number.string' => 'رقم رخصة القيادة يجب أن يكون نص',
            'driving_license_number.max' => 'رقم رخصة القيادة لا يجب أن يتجاوز 255 حرف',

            'religion_id.exists' => 'الديانة المختارة غير موجودة',
            'qualifications_id.exists' => 'المؤهل المختار غير موجود',

            'qualification_year.string' => 'سنة التخرج يجب أن يكون نص',
            'qualification_year.max' => 'سنة التخرج لا يجب أن تتجاوز 50 حرف',

            'graduation_grade.in' => 'التقدير المختار غير صحيح',

            'graduation_specialization.string' => 'تخصص التخرج يجب أن يكون نص',
            'graduation_specialization.max' => 'تخصص التخرج لا يجب أن يتجاوز 255 حرف',

            'hire_date.date' => 'تاريخ التعيين يجب أن يكون تاريخ صحيح',
            'hire_date_day_month_year.date' => 'تاريخ التعيين (يوم/شهر/سنة) يجب أن يكون تاريخ صحيح',

            'employment_status.required' => 'حالة التوظيف مطلوبة',
            'employment_status.in' => 'حالة التوظيف المختارة غير صحيحة',

            'resignation_id.exists' => 'نوع الاستقالة المختار غير موجود',
            'resignation_date.date' => 'تاريخ الاستقالة يجب أن يكون تاريخ صحيح',
            'resignation_reason.string' => 'سبب الاستقالة يجب أن يكون نص',
            'resignation_reason.max' => 'سبب الاستقالة لا يجب أن يتجاوز 500 حرف',

            'motivation_type.in' => 'نوع الحافز المختار غير صحيح',
            'motivation_amount.numeric' => 'قيمة الحافز يجب أن تكون رقم',
            'motivation_amount.min' => 'قيمة الحافز لا يمكن أن تكون أقل من 0',

            'payment_method.in' => 'طريقة الدفع المختارة غير صحيحة',

            'bank_account_number.string' => 'رقم الحساب البنكي يجب أن يكون نص',
            'bank_account_number.max' => 'رقم الحساب البنكي لا يجب أن يتجاوز 255 حرف',

            'has_disability.in' => 'حقل الإعاقة غير صحيح',
            'disability_description.string' => 'وصف الإعاقة يجب أن يكون نص',
            'disability_description.max' => 'وصف الإعاقة لا يجب أن يتجاوز 500 حرف',

            'has_relative.in' => 'حقل وجود قريب غير صحيح',
            'relative_description.string' => 'وصف القريب يجب أن يكون نص',
            'relative_description.max' => 'وصف القريب لا يجب أن يتجاوز 500 حرف',

            'urgent_contact_details.string' => 'بيانات الاتصال في الحالات الطارئة يجب أن تكون نص',
            'urgent_contact_details.max' => 'بيانات الاتصال في الحالات الطارئة لا يجب أن تتجاوز 1000 حرف',

            'daily_work_hours.numeric' => 'عدد ساعات العمل اليومية يجب أن يكون رقم',
            'daily_work_hours.min' => 'عدد ساعات العمل اليومية لا يمكن أن يكون أقل من 0',

            'job_id.required' => 'الوظيفة مطلوبة',
            'job_id.exists' => 'الوظيفة المختارة غير موجودة',

            'department_id.required' => 'القسم مطلوب',
            'department_id.exists' => 'القسم المختار غير موجود',

            'nationality_id.required' => 'الجنسية مطلوبة',
            'nationality_id.exists' => 'الجنسية المختارة غير موجودة',

            'nationality_number.string' => 'رقم الجنسية يجب أن يكون نص',
            'nationality_number.max' => 'رقم الجنسية لا يجب أن يتجاوز 255 حرف',

            'nationality_expiry_date.date' => 'تاريخ انتهاء الجنسية يجب أن يكون تاريخ صحيح',
            'nationality_place_of_issue.string' => 'مكان إصدار الجنسية يجب أن يكون نص',
            'nationality_place_of_issue.max' => 'مكان إصدار الجنسية لا يجب أن يتجاوز 255 حرف',

            'sponsor_name.string' => 'اسم الكفيل يجب أن يكون نص',
            'sponsor_name.max' => 'اسم الكفيل لا يجب أن يتجاوز 255 حرف',

            'passport_number.string' => 'رقم الجواز يجب أن يكون نص',
            'passport_number.max' => 'رقم الجواز لا يجب أن يتجاوز 255 حرف',

            'passport_expiry_date.date' => 'تاريخ انتهاء الجواز يجب أن يكون تاريخ صحيح',
            'passport_place_of_issue.string' => 'مكان إصدار الجواز يجب أن يكون نص',
            'passport_place_of_issue.max' => 'مكان إصدار الجواز لا يجب أن يتجاوز 255 حرف',

            'image.image' => 'الملف المرفق يجب أن يكون صورة',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجا',

            'salary.numeric' => 'الراتب يجب أن يكون رقم',
            'salary.min' => 'الراتب لا يمكن أن يكون أقل من 0',

            'lang_id.integer' => 'كود اللغة يجب أن يكون رقم',

            'company_id.required' => 'الشركة مطلوبة',
            'company_id.integer' => 'الشركة يجب أن تكون رقم',

            'added_by.required' => 'منشئ الموظف مطلوب',
            'added_by.integer' => 'منشئ الموظف يجب أن يكون رقم',
            'added_by.exists' => 'الأدمن المنشئ غير موجود',

            'updated_by.integer' => 'محدث الموظف يجب أن يكون رقم',
            'updated_by.exists' => 'الأدمن المحدث غير موجود',

            'fixed_shift.in' => 'قيمة الشيفت الثابت غير صحيحة',
            'shift_type_id.exists' => 'نوع الشيفت المختار غير موجود',

            'payment_per_day.numeric' => 'الأجر اليومي يجب أن يكون رقم',
            'payment_per_day.min' => 'الأجر اليومي لا يمكن أن يكون أقل من 0',

            'has_social_insurance.in' => 'حقل التأمين الاجتماعي غير صحيح',
            'social_insurance_amount.numeric' => 'قيمة التأمين الاجتماعي يجب أن تكون رقم',
            'social_insurance_amount.min' => 'قيمة التأمين الاجتماعي لا يمكن أن تكون أقل من 0',
            'social_insurance_number.string' => 'رقم التأمين الاجتماعي يجب أن يكون نص',
            'social_insurance_number.max' => 'رقم التأمين الاجتماعي لا يجب أن يتجاوز 255 حرف',

            'has_medical_insurance.in' => 'حقل التأمين الطبي غير صحيح',
            'medical_insurance_amount.numeric' => 'قيمة التأمين الطبي يجب أن تكون رقم',
            'medical_insurance_amount.min' => 'قيمة التأمين الطبي لا يمكن أن تكون أقل من 0',

            'fixed_allowance.in' => 'حقل العلاوة الثابتة غير صحيح',
            'has_attendance.in' => 'حقل الحضور والانصراف غير صحيح',

            'vacation_formula.integer' => 'صيغة الإجازات يجب أن تكون رقم',
            'active_for_vacation.in' => 'حقل تفعيل الإجازات غير صحيح',
            'has_sensitive_data.in' => 'حقل البيانات الحساسة غير صحيح',

            'branch_id.required' => 'الفرع مطلوب',
            'branch_id.exists' => 'الفرع المختار غير موجود',
        ];
    }
}
