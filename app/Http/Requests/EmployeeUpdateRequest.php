<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $employeeId = $this->route('id');

        return [
            // ==================== بيانات الموظف الرئيسية ====================
            'fingerprint_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('employees', 'fingerprint_code')->ignore($employeeId)
            ],
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'nationality_id' => 'required|exists:nationalities,id',
            'gender' => 'required|in:1,2,3',
            'religion_id' => 'nullable|exists:religions,id',
            'nationality_number' => 'nullable|string|max:255',
            'nationality_expiry_date' => 'nullable|date',
            'nationality_place_of_issue' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('employees', 'email')->ignore($employeeId)
            ],
            'home_telephone' => 'nullable|string|max:50',
            'work_telephone' => 'nullable|string|max:50',
            'marital_status' => 'nullable|in:1,2,3,4,5',
            'children_count' => 'nullable|integer|min:0',
            'stable_address' => 'nullable|string|max:500',
            'country_id' => 'nullable|exists:countries,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'city_id' => 'nullable|exists:cities,id',
            'home_address' => 'nullable|string|max:500',
            'blood_group_id' => 'nullable|exists:blood_groups,id',

            // ==================== رخصة القيادة ====================
            'driving_license' => 'nullable|in:0,1',
            'driving_license_type_id' => 'nullable|exists:driving_license_types,id',
            'driving_license_number' => 'nullable|string|max:255',

            // ==================== الحالة العسكرية ====================
            'military_status' => 'nullable|exists:military_statuses,id',
            'military_start_date' => 'nullable|date',
            'military_end_date' => 'nullable|date',
            'military_weapon' => 'nullable|string|max:255',
            'military_exemption_date' => 'nullable|date',
            'military_exemption_reason' => 'nullable|string|max:500',
            'postponement_reason' => 'nullable|string|max:500',

            // ==================== المؤهلات والوظيفة ====================
            'qualifications_id' => 'nullable|exists:qualifications,id',
            'qualification_year' => 'nullable|string|max:50',
            'graduation_grade' => 'nullable|in:1,2,3,4,5',
            'graduation_specialization' => 'nullable|string|max:255',
            'job_id' => 'required|exists:jobs_categories,id',
            'department_id' => 'required|exists:departments,id',
            'branch_id' => 'required|exists:branches,id',
            'hire_date' => 'nullable|date',
            'hire_date_day_month_year' => 'nullable|date',
            'employment_status' => 'required|in:0,1',
            'fixed_shift' => 'nullable|in:0,1',
            'shift_type_id' => 'nullable|exists:shifts_types,id',
            'daily_work_hours' => 'nullable|numeric|min:0',
            'resignation_id' => 'nullable|exists:resignations,id',
            'resignation_date' => 'nullable|date',
            'resignation_reason' => 'nullable|string|max:500',

            // ==================== البيانات المالية ====================
            'salary' => 'nullable|numeric|min:0',
            'motivation_type' => 'nullable|in:0,1,2',
            'motivation_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:1,2,3',
            'bank_account_number' => 'nullable|string|max:255',

            // ==================== التأمينات ====================
            'has_social_insurance' => 'nullable|in:0,1',
            'social_insurance_amount' => 'nullable|numeric|min:0',
            'social_insurance_number' => 'nullable|string|max:255',
            'has_medical_insurance' => 'nullable|in:0,1',
            'medical_insurance_number' => 'nullable|string|max:255',
            'medical_insurance_amount' => 'nullable|numeric|min:0',

            // ==================== إعدادات إضافية ====================
            'fixed_allowance' => 'nullable|in:0,1',
            'has_attendance' => 'nullable|in:0,1',
            'active_for_vacation' => 'nullable|in:0,1',
            'has_sensitive_data' => 'nullable|in:0,1',

            // ==================== بيانات إضافية ====================
            'sponsor_name' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'passport_expiry_date' => 'nullable|date',
            'passport_place_of_issue' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
            'cv' => 'nullable|file|mimes:pdf,doc,docx,zip,rar,jpg,png,jpeg|max:5120',
            'language_id' => 'nullable|exists:languages,id',
            'has_disability' => 'nullable|in:0,1',
            'disability_description' => 'nullable|string|max:500',
            'has_relative' => 'nullable|in:0,1',
            'relative_description' => 'nullable|string|max:500',
            'urgent_contact_details' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',

        ];
    }

    public function messages()
    {
        return [
            // ==================== بيانات الموظف الرئيسية ====================
            'fingerprint_code.string' => 'معرف البصمة يجب أن يكون نص',
            'fingerprint_code.max' => 'معرف البصمة لا يجب أن يتجاوز 255 حرف',
            'fingerprint_code.unique' => 'معرف البصمة مستخدم من قبل',

            'name.required' => 'اسم الموظف مطلوب',
            'name.string' => 'اسم الموظف يجب أن يكون نص',
            'name.max' => 'اسم الموظف لا يجب أن يتجاوز 255 حرف',

            'birth_date.date' => 'تاريخ الميلاد يجب أن يكون تاريخ صحيح',

            'nationality_id.required' => 'الجنسية مطلوبة',
            'nationality_id.exists' => 'الجنسية المختارة غير موجودة',

            'gender.required' => 'الجنس مطلوب',
            'gender.in' => 'الجنس المختار غير صحيح',

            'religion_id.exists' => 'الديانة المختارة غير موجودة',

            'nationality_number.string' => 'رقم البطاقة يجب أن يكون نص',
            'nationality_number.max' => 'رقم البطاقة لا يجب أن يتجاوز 255 حرف',

            'nationality_expiry_date.date' => 'تاريخ انتهاء البطاقة غير صحيح',

            'nationality_place_of_issue.string' => 'مكان إصدار البطاقة يجب أن يكون نص',
            'nationality_place_of_issue.max' => 'مكان إصدار البطاقة طويل جداً',

            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',

            'home_telephone.string' => 'هاتف المنزل يجب أن يكون نص',
            'home_telephone.max' => 'هاتف المنزل لا يجب أن يتجاوز 50 حرف',

            'work_telephone.string' => 'هاتف العمل يجب أن يكون نص',
            'work_telephone.max' => 'هاتف العمل لا يجب أن يتجاوز 50 حرف',

            'marital_status.in' => 'الحالة الاجتماعية المختارة غير صحيحة',

            'children_count.integer' => 'عدد الأطفال يجب أن يكون رقم',
            'children_count.min' => 'عدد الأطفال لا يمكن أن يكون أقل من 0',

            'stable_address.string' => 'عنوان الإقامة يجب أن يكون نص',
            'stable_address.max' => 'عنوان الإقامة طويل جداً',

            'country_id.exists' => 'الدولة المختارة غير موجودة',
            'governorate_id.exists' => 'المحافظة المختارة غير موجودة',
            'city_id.exists' => 'المدينة المختارة غير موجودة',

            'home_address.string' => 'عنوان بلد الأصل يجب أن يكون نص',
            'home_address.max' => 'عنوان بلد الأصل طويل جداً',

            'blood_group_id.exists' => 'فصيلة الدم المختارة غير موجودة',

            // ==================== رخصة القيادة ====================
            'driving_license.in' => 'حالة رخصة القيادة غير صحيحة',
            'driving_license_type_id.exists' => 'نوع رخصة القيادة المختار غير موجود',
            'driving_license_number.string' => 'رقم رخصة القيادة يجب أن يكون نص',
            'driving_license_number.max' => 'رقم رخصة القيادة طويل جداً',

            // ==================== الحالة العسكرية ====================
            'military_status.exists' => 'الحالة العسكرية المختارة غير موجودة',
            'military_start_date.date' => 'تاريخ بداية الخدمة العسكرية غير صحيح',
            'military_end_date.date' => 'تاريخ نهاية الخدمة العسكرية غير صحيح',
            'military_weapon.string' => 'سلاح الخدمة يجب أن يكون نص',
            'military_weapon.max' => 'سلاح الخدمة طويل جداً',
            'military_exemption_date.date' => 'تاريخ الإعفاء غير صحيح',
            'military_exemption_reason.string' => 'سبب الإعفاء يجب أن يكون نص',
            'military_exemption_reason.max' => 'سبب الإعفاء طويل جداً',
            'postponement_reason.string' => 'سبب التأجيل يجب أن يكون نص',
            'postponement_reason.max' => 'سبب التأجيل طويل جداً',

            // ==================== المؤهلات والوظيفة ====================
            'qualifications_id.exists' => 'المؤهل المختار غير موجود',
            'qualification_year.string' => 'سنة التخرج يجب أن يكون نص',
            'qualification_year.max' => 'سنة التخرج طويلة جداً',
            'graduation_grade.in' => 'تقدير التخرج غير صحيح',
            'graduation_specialization.string' => 'تخصص التخرج يجب أن يكون نص',
            'graduation_specialization.max' => 'تخصص التخرج طويل جداً',

            'job_id.required' => 'الوظيفة مطلوبة',
            'job_id.exists' => 'الوظيفة المختارة غير موجودة',

            'department_id.required' => 'الإدارة مطلوبة',
            'department_id.exists' => 'الإدارة المختارة غير موجودة',

            'branch_id.required' => 'الفرع مطلوب',
            'branch_id.exists' => 'الفرع المختار غير موجود',

            'hire_date.date' => 'تاريخ التعيين غير صحيح',
            'hire_date_day_month_year.date' => 'تاريخ التعيين (يوم/شهر/سنة) غير صحيح',

            'employment_status.required' => 'حالة التوظيف مطلوبة',
            'employment_status.in' => 'حالة التوظيف غير صحيحة',

            'fixed_shift.in' => 'حالة الشيفت الثابت غير صحيحة',
            'shift_type_id.exists' => 'نوع الشيفت المختار غير موجود',
            'daily_work_hours.numeric' => 'عدد ساعات العمل يجب أن يكون رقم',
            'daily_work_hours.min' => 'عدد ساعات العمل لا يمكن أن يكون أقل من 0',

            'resignation_id.exists' => 'حالة الاستقالة المختارة غير موجودة',
            'resignation_date.date' => 'تاريخ الاستقالة غير صحيح',
            'resignation_reason.string' => 'سبب الاستقالة يجب أن يكون نص',
            'resignation_reason.max' => 'سبب الاستقالة طويل جداً',

            // ==================== البيانات المالية ====================
            'salary.numeric' => 'الراتب يجب أن يكون رقم',
            'salary.min' => 'الراتب لا يمكن أن يكون أقل من 0',

            'motivation_type.in' => 'نوع الحافز غير صحيح',
            'motivation_amount.numeric' => 'مبلغ الحافز يجب أن يكون رقم',
            'motivation_amount.min' => 'مبلغ الحافز لا يمكن أن يكون أقل من 0',

            'payment_method.in' => 'طريقة الدفع غير صحيحة',
            'bank_account_number.string' => 'رقم الحساب البنكي يجب أن يكون نص',
            'bank_account_number.max' => 'رقم الحساب البنكي طويل جداً',

            // ==================== التأمينات ====================
            'has_social_insurance.in' => 'حالة التأمين الاجتماعي غير صحيحة',
            'social_insurance_amount.numeric' => 'مبلغ التأمين الاجتماعي يجب أن يكون رقم',
            'social_insurance_amount.min' => 'مبلغ التأمين الاجتماعي لا يمكن أن يكون أقل من 0',
            'social_insurance_number.string' => 'رقم التأمين الاجتماعي يجب أن يكون نص',
            'social_insurance_number.max' => 'رقم التأمين الاجتماعي طويل جداً',

            'has_medical_insurance.in' => 'حالة التأمين الطبي غير صحيحة',
            'medical_insurance_number.string' => 'رقم التأمين الطبي يجب أن يكون نص',
            'medical_insurance_number.max' => 'رقم التأمين الطبي طويل جداً',
            'medical_insurance_amount.numeric' => 'مبلغ التأمين الطبي يجب أن يكون رقم',
            'medical_insurance_amount.min' => 'مبلغ التأمين الطبي لا يمكن أن يكون أقل من 0',

            // ==================== إعدادات إضافية ====================
            'fixed_allowance.in' => 'حالة البدل الثابت غير صحيحة',
            'has_attendance.in' => 'حالة الحضور غير صحيحة',
            'active_for_vacation.in' => 'حالة التفعيل للإجازات غير صحيحة',
            'has_sensitive_data.in' => 'حالة البيانات الحساسة غير صحيحة',

            // ==================== بيانات إضافية ====================
            'sponsor_name.string' => 'اسم الكفيل يجب أن يكون نص',
            'sponsor_name.max' => 'اسم الكفيل طويل جداً',

            'passport_number.string' => 'رقم الجواز يجب أن يكون نص',
            'passport_number.max' => 'رقم الجواز طويل جداً',

            'passport_expiry_date.date' => 'تاريخ انتهاء الجواز غير صحيح',

            'passport_place_of_issue.string' => 'مكان إصدار الجواز يجب أن يكون نص',
            'passport_place_of_issue.max' => 'مكان إصدار الجواز طويل جداً',

            'image.image' => 'الملف المرفق يجب أن يكون صورة',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 5 ميجا',

            'cv.file' => 'الملف المرفق غير صحيح',
            'cv.mimes' => 'الملف المرفق يجب أن يكون من نوع pdf, doc, docx, zip, rar, jpg, png, jpeg',
            'cv.max' => 'حجم السيرة الذاتية لا يجب أن يتجاوز 5 ميجا',

            'language_id.exists' => 'اللغة المختارة غير موجودة',

            'has_disability.in' => 'حالة الإعاقة غير صحيحة',
            'disability_description.string' => 'وصف الإعاقة يجب أن يكون نص',
            'disability_description.max' => 'وصف الإعاقة طويل جداً',

            'has_relative.in' => 'حالة وجود قريب غير صحيحة',
            'relative_description.string' => 'وصف القريب يجب أن يكون نص',
            'relative_description.max' => 'وصف القريب طويل جداً',

            'urgent_contact_details.string' => 'بيانات الاتصال العاجلة يجب أن تكون نص',
            'urgent_contact_details.max' => 'بيانات الاتصال العاجلة طويلة جداً',

            'notes.string' => 'الملاحظات يجب أن تكون نص',
            'notes.max' => 'الملاحظات طويلة جداً',
        ];
    }
}