<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $employee_code Employee Code unique identifier
 * @property string|null $fingerprint_code Fingerprint Code unique identifier
 * @property string $name
 * @property string|null $birth_date
 * @property int $nationality_id
 * @property int $gender 1: Male, 2: Female, 3: Other
 * @property int|null $religion_id
 * @property string|null $nationality_number
 * @property string|null $nationality_expiry_date
 * @property string|null $nationality_place_of_issue
 * @property string|null $email
 * @property string|null $home_telephone
 * @property string|null $work_telephone
 * @property int|null $marital_status 1: Single, 2: Married, 3: engaged , 4: Widowed , 5: Divorced
 * @property int|null $children_count
 * @property string|null $stable_address
 * @property int|null $country_id
 * @property int|null $governorate_id
 * @property int|null $city_id
 * @property string|null $home_address
 * @property int|null $blood_group_id
 * @property int|null $driving_license 1: Yes, 0: No
 * @property int|null $driving_license_type_id
 * @property string|null $driving_license_number
 * @property int|null $military_status
 * @property string|null $military_start_date
 * @property string|null $military_end_date
 * @property string|null $military_weapon
 * @property string|null $military_exemption_date
 * @property string|null $military_exemption_reason
 * @property string|null $postponement_reason
 * @property int|null $qualifications_id
 * @property string|null $qualification_year
 * @property int|null $graduation_grade 1: Excellent, 2: Very Good, 3: Good, 4: Fair, 5: Poor
 * @property string|null $graduation_specialization
 * @property int $job_id
 * @property int $department_id
 * @property int $branch_id
 * @property string|null $hire_date
 * @property string|null $hire_date_day_month_year
 * @property int $employment_status 1: Active, 0: Inactive
 * @property int|null $fixed_shift
 * @property int|null $shift_type_id
 * @property numeric|null $daily_work_hours
 * @property int|null $resignation_id
 * @property string|null $resignation_date
 * @property string|null $resignation_reason
 * @property numeric|null $salary
 * @property int|null $motivation_type 0: None, 1: Fixed, 2: Variable
 * @property numeric|null $motivation_amount
 * @property int|null $payment_method 1: Cash, 2: Bank Transfer, 3: Check
 * @property string|null $bank_account_number
 * @property numeric|null $payment_per_day
 * @property int|null $has_social_insurance 1: Yes, 0: No
 * @property numeric|null $social_insurance_amount
 * @property string|null $social_insurance_number
 * @property int|null $has_medical_insurance 1: Yes, 0: No
 * @property string|null $medical_insurance_number
 * @property numeric|null $medical_insurance_amount
 * @property int|null $fixed_allowance
 * @property int|null $has_attendance 1: Yes, 0: No
 * @property int|null $vacation_formula
 * @property int|null $active_for_vacation
 * @property int|null $has_sensitive_data
 * @property string|null $sponsor_name
 * @property string|null $passport_number
 * @property string|null $passport_expiry_date
 * @property string|null $passport_place_of_issue
 * @property string|null $image
 * @property string|null $cv
 * @property int|null $language_id
 * @property int|null $has_disability 1: Yes, 0: No
 * @property string|null $disability_description
 * @property int|null $has_relative 1: Yes, 0: No
 * @property string|null $relative_description
 * @property string|null $urgent_contact_details
 * @property string|null $notes
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\BloodGroup|null $bloodGroup
 * @property-read \App\Models\Branche $branch
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Department $department
 * @property-read \App\Models\DrivingLicenseType|null $drivingLicenseType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EmployeeFixedAllowance> $employeeFixedAllowances
 * @property-read int|null $employee_fixed_allowances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\Governorate|null $governorate
 * @property-read \App\Models\JobsCategory $job
 * @property-read \App\Models\Language|null $language
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployee> $mainSalaryEmployee
 * @property-read int|null $main_salary_employee_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAbsence> $mainSalaryEmployeeAbsences
 * @property-read int|null $main_salary_employee_absences_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAddition> $mainSalaryEmployeeAdditions
 * @property-read int|null $main_salary_employee_additions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAllowance> $mainSalaryEmployeeAllowances
 * @property-read int|null $main_salary_employee_allowances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeBonus> $mainSalaryEmployeeBonuses
 * @property-read int|null $main_salary_employee_bonuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeDeductionType> $mainSalaryEmployeeDeductionTypes
 * @property-read int|null $main_salary_employee_deduction_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeDeduction> $mainSalaryEmployeeDeductions
 * @property-read int|null $main_salary_employee_deductions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeLoan> $mainSalaryEmployeeLoans
 * @property-read int|null $main_salary_employee_loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeePLoan> $mainSalaryEmployeePLoans
 * @property-read int|null $main_salary_employee_p_loans_count
 * @property-read \App\Models\MilitaryStatus|null $militaryStatus
 * @property-read \App\Models\Nationality $nationality
 * @property-read \App\Models\Qualification|null $qualification
 * @property-read \App\Models\Religion|null $religion
 * @property-read \App\Models\Resignation|null $resignation
 * @property-read \App\Models\ShiftsType|null $shiftType
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereActiveForVacation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBankAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBloodGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereChildrenCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDailyWorkHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDisabilityDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDrivingLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDrivingLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDrivingLicenseTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmployeeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmploymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFingerprintCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFixedAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFixedShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGovernorateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGraduationGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGraduationSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasAttendance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasDisability($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasMedicalInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasRelative($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasSensitiveData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasSocialInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHireDateDayMonthYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHomeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHomeTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMedicalInsuranceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMedicalInsuranceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryExemptionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryExemptionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMilitaryWeapon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMotivationAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMotivationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationalityExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationalityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationalityPlaceOfIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePassportExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePassportNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePassportPlaceOfIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePaymentPerDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePostponementReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereQualificationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereQualificationsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereRelativeDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereReligionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereResignationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereResignationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereResignationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereShiftTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSocialInsuranceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSocialInsuranceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSponsorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereStableAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUrgentContactDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereVacationFormula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereWorkTelephone($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class Employee extends Model
{


    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function resignation()
    {
        return $this->belongsTo(Resignation::class, 'resignation_id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id');
    }

    public function qualification()
    {
        return $this->belongsTo(Qualification::class, 'qualifications_id');
    }

    public function job()
    {
        return $this->belongsTo(JobsCategory::class, 'job_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }

    public function shiftType()
    {
        return $this->belongsTo(ShiftsType::class, 'shift_type_id');
    }
    public function bloodGroup()
    {
        return $this->belongsTo(BloodGroup::class, 'blood_group_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branche::class, 'branch_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function militaryStatus()
    {
        return $this->belongsTo(MilitaryStatus::class, 'military_status_id');
    }
    public function drivingLicenseType()
    {
        return $this->belongsTo(DrivingLicenseType::class, 'driving_license_type_id');
    }
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
    public function files()
    {
        return $this->hasMany(File::class, 'employee_id');
    }
    public function mainSalaryEmployee(){
        return $this->hasMany(MainSalaryEmployee::class, 'employee_id');
    }
    public function mainEmployeesVacationsBalances()
    {
        return $this->hasMany(MainEmployeesVacationsBalances::class, 'employee_id');
    }
    public function mainSalaryEmployeeDeductions(){
        return $this->hasMany(MainSalaryEmployeeDeduction::class, 'employee_id');
    }
    public function mainSalaryEmployeeAbsences(){
        return $this->hasMany(MainSalaryEmployeeAbsence::class, 'employee_id');
    }
    public function mainSalaryEmployeeDeductionTypes()
    {
        return $this->hasMany(MainSalaryEmployeeDeductionType::class, 'employee_id');
    }
    public function mainSalaryEmployeeAdditions()
    {
        return $this->hasMany(MainSalaryEmployeeAddition::class, 'employee_id');
    }
    public function mainSalaryEmployeeLoans()
    {
        return $this->hasMany(MainSalaryEmployeeLoan::class, 'employee_id');
    }
    public function mainSalaryEmployeeBonuses()
    {
        return $this->hasMany(MainSalaryEmployeeBonus::class, 'employee_id');
    }
    public function mainSalaryEmployeeAllowances()
    {
        return $this->hasMany(MainSalaryEmployeeAllowance::class, 'employee_id');
    }
    public function mainSalaryEmployeePLoans()
    {
        return $this->hasMany(MainSalaryEmployeePLoan::class, 'employee_id');
    }
    public function employeeFixedAllowances()
    {
        return $this->hasMany(EmployeeFixedAllowance::class, 'employee_id');
    }
    public function employeeSalaryArchives()
    {
        return $this->hasMany(EmployeeSalaryArchive::class, 'employee_id');
    }
    public function attendanceDepartureActionsExcel()
    {
        return $this->hasMany(AttendanceDepartureActionsExcel::class);
    }
    public function attendancesDepartures()
    {
        return $this->hasMany(AttendanceDeparture::class, 'employee_id');
    }
    public function attendancesDeparturesActions()
    {
        return $this->hasMany(AttendanceDepartureAction::class, 'employee_id');
    }
}
