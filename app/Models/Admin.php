<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string $username
 * @property string $password
 * @property string $added_by
 * @property string $updated_by
 * @property int $status
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdminPanelSetting> $addedAdminPanels
 * @property-read int|null $added_admin_panels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AllowanceType> $addedAllowanceTypes
 * @property-read int|null $added_allowance_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodGroup> $addedBloodGroup
 * @property-read int|null $added_blood_group_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bonus> $addedBonuses
 * @property-read int|null $added_bonuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\City> $addedCities
 * @property-read int|null $added_cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Country> $addedCountries
 * @property-read int|null $added_countries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeductionType> $addedDeductionTypes
 * @property-read int|null $added_deduction_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Department> $addedDepartments
 * @property-read int|null $added_departments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $addedEmployees
 * @property-read int|null $added_employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $addedFiles
 * @property-read int|null $added_files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceCalendar> $addedFinanceCalendars
 * @property-read int|null $added_finance_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceMonthlyCalendar> $addedFinanceMonthlyCalendars
 * @property-read int|null $added_finance_monthly_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Governorate> $addedGovernorates
 * @property-read int|null $added_governorates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobsCategory> $addedJobsCategoies
 * @property-read int|null $added_jobs_categoies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAbsence> $addedMainSalaryEmployeeAbsences
 * @property-read int|null $added_main_salary_employee_absences_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAddition> $addedMainSalaryEmployeeAdditions
 * @property-read int|null $added_main_salary_employee_additions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAllowance> $addedMainSalaryEmployeeAllowances
 * @property-read int|null $added_main_salary_employee_allowances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeBonus> $addedMainSalaryEmployeeBonuses
 * @property-read int|null $added_main_salary_employee_bonuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeDeductionType> $addedMainSalaryEmployeeDeductionTypes
 * @property-read int|null $added_main_salary_employee_deduction_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeDeduction> $addedMainSalaryEmployeeDeductions
 * @property-read int|null $added_main_salary_employee_deductions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeLoan> $addedMainSalaryEmployeeLoans
 * @property-read int|null $added_main_salary_employee_loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeePLoanInstallment> $addedMainSalaryEmployeePLoanInstallments
 * @property-read int|null $added_main_salary_employee_p_loan_installments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeePLoan> $addedMainSalaryEmployeePLoans
 * @property-read int|null $added_main_salary_employee_p_loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployee> $addedMainSalaryEmployees
 * @property-read int|null $added_main_salary_employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Nationality> $addedNationality
 * @property-read int|null $added_nationality_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Occasion> $addedOccasions
 * @property-read int|null $added_occasions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Qualification> $addedQualifications
 * @property-read int|null $added_qualifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Religion> $addedReligion
 * @property-read int|null $added_religion_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resignation> $addedResignation
 * @property-read int|null $added_resignation_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShiftsType> $addedShiftsTypes
 * @property-read int|null $added_shifts_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAddition> $archivedMainSalaryEmployeeAdditions
 * @property-read int|null $archived_main_salary_employee_additions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAllowance> $archivedMainSalaryEmployeeAllowances
 * @property-read int|null $archived_main_salary_employee_allowances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeBonus> $archivedMainSalaryEmployeeBonuses
 * @property-read int|null $archived_main_salary_employee_bonuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeDeductionType> $archivedMainSalaryEmployeeDeductionTypes
 * @property-read int|null $archived_main_salary_employee_deduction_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeDeduction> $archivedMainSalaryEmployeeDeductions
 * @property-read int|null $archived_main_salary_employee_deductions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeLoan> $archivedMainSalaryEmployeeLoans
 * @property-read int|null $archived_main_salary_employee_loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeePLoanInstallment> $archivedMainSalaryEmployeePLoanInstallments
 * @property-read int|null $archived_main_salary_employee_p_loan_installments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeePLoan> $archivedMainSalaryEmployeePLoans
 * @property-read int|null $archived_main_salary_employee_p_loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Branche> $branches
 * @property-read int|null $branches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeePLoan> $disbursedMainSalaryEmployeePLoans
 * @property-read int|null $disbursed_main_salary_employee_p_loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EmployeeFixedAllowance> $employeeFixedAllowances
 * @property-read int|null $employee_fixed_allowances_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdminPanelSetting> $updatedAdminPanels
 * @property-read int|null $updated_admin_panels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AllowanceType> $updatedAllowanceTypes
 * @property-read int|null $updated_allowance_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodGroup> $updatedBloodGroup
 * @property-read int|null $updated_blood_group_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bonus> $updatedBonuses
 * @property-read int|null $updated_bonuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Branche> $updatedBranches
 * @property-read int|null $updated_branches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\City> $updatedCities
 * @property-read int|null $updated_cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Country> $updatedCountries
 * @property-read int|null $updated_countries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeductionType> $updatedDeductionTypes
 * @property-read int|null $updated_deduction_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Department> $updatedDepartments
 * @property-read int|null $updated_departments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EmployeeFixedAllowance> $updatedEmployeeFixedAllowances
 * @property-read int|null $updated_employee_fixed_allowances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $updatedEmployees
 * @property-read int|null $updated_employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $updatedFiles
 * @property-read int|null $updated_files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceCalendar> $updatedFinanceCalendars
 * @property-read int|null $updated_finance_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceMonthlyCalendar> $updatedFinanceMonthlyCalendars
 * @property-read int|null $updated_finance_monthly_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Governorate> $updatedGovernorates
 * @property-read int|null $updated_governorates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobsCategory> $updatedJobsCategoies
 * @property-read int|null $updated_jobs_categoies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAbsence> $updatedMainSalaryEmployeeAbsences
 * @property-read int|null $updated_main_salary_employee_absences_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAddition> $updatedMainSalaryEmployeeAdditions
 * @property-read int|null $updated_main_salary_employee_additions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeAllowance> $updatedMainSalaryEmployeeAllowances
 * @property-read int|null $updated_main_salary_employee_allowances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeBonus> $updatedMainSalaryEmployeeBonuses
 * @property-read int|null $updated_main_salary_employee_bonuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeDeductionType> $updatedMainSalaryEmployeeDeductionTypes
 * @property-read int|null $updated_main_salary_employee_deduction_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeDeduction> $updatedMainSalaryEmployeeDeductions
 * @property-read int|null $updated_main_salary_employee_deductions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeeLoan> $updatedMainSalaryEmployeeLoans
 * @property-read int|null $updated_main_salary_employee_loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeePLoanInstallment> $updatedMainSalaryEmployeePLoanInstallments
 * @property-read int|null $updated_main_salary_employee_p_loan_installments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployeePLoan> $updatedMainSalaryEmployeePLoans
 * @property-read int|null $updated_main_salary_employee_p_loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MainSalaryEmployee> $updatedMainSalaryEmployees
 * @property-read int|null $updated_main_salary_employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Nationality> $updatedNationality
 * @property-read int|null $updated_nationality_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Occasion> $updatedOccasions
 * @property-read int|null $updated_occasions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Qualification> $updatedQualifications
 * @property-read int|null $updated_qualifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Religion> $updatedReligion
 * @property-read int|null $updated_religion_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resignation> $updatedResignation
 * @property-read int|null $updated_resignation_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShiftsType> $updatedShiftsTypes
 * @property-read int|null $updated_shifts_types_count
 * @method static \Database\Factories\AdminFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUsername($value)
 * @mixin \Eloquent
 */

use App\Traits\LogsActivity;

#[Fillable(['name', 'email', 'username', 'password', 'added_by', 'updated_by', 'status', 'date', 'created_at', 'updated_at', 'company_id', 'image', 'phone', 'address', 'birth_date', 'national_id', 'gender', 'bio', 'is_master_admin', 'permission_role_id'])]
#[Hidden(['password', 'remember_token'])]
class Admin extends User
{
    use HasFactory, LogsActivity;

    public function permissionRole()
    {
        return $this->belongsTo(PermissionRole::class, 'permission_role_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function addedAdminPanels()
    {
        return $this->hasMany(AdminPanelSetting::class, 'created_by');
    }

    public function updatedAdminPanels()
    {
        return $this->hasMany(AdminPanelSetting::class, 'updated_by');
    }
    public function addedFinanceCalendars()
    {
        return $this->hasMany(FinanceCalendar::class, 'added_by');
    }

    public function updatedFinanceCalendars()
    {
        return $this->hasMany(FinanceCalendar::class, 'updated_by');
    }
    public function addedFinanceMonthlyCalendars()
    {
        return $this->hasMany(FinanceMonthlyCalendar::class, 'added_by');
    }

    public function updatedFinanceMonthlyCalendars()
    {
        return $this->hasMany(FinanceMonthlyCalendar::class, 'updated_by');
    }
    public function branches()
    {
        return $this->hasMany(Branche::class, 'added_by');
    }
    public function updatedBranches()
    {
        return $this->hasMany(Branche::class, 'updated_by');
    }
    public function addedShiftsTypes()
    {
        return $this->hasMany(ShiftsType::class, 'added_by');
    }
    public function updatedShiftsTypes()
    {
        return $this->hasMany(ShiftsType::class, 'updated_by');
    }
    public function addedDepartments()
    {
        return $this->hasMany(Department::class, 'added_by');
    }
    public function updatedDepartments()
    {
        return $this->hasMany(Department::class, 'updated_by');
    }
    public function addedJobsCategoies()
    {
        return $this->hasMany(JobsCategory::class, 'added_by');
    }
    public function updatedJobsCategoies()
    {
        return $this->hasMany(JobsCategory::class, 'updated_by');
    }
    public function addedQualifications()
    {
        return $this->hasMany(Qualification::class, 'added_by');
    }
    public function updatedQualifications()
    {
        return $this->hasMany(Qualification::class, 'updated_by');
    }
    public function addedOccasions()
    {
        return $this->hasMany(Occasion::class, 'added_by');
    }
    public function updatedOccasions()
    {
        return $this->hasMany(Occasion::class, 'updated_by');
    }
    public function addedResignation()
    {
        return $this->hasMany(Resignation::class, 'added_by');
    }
    public function updatedResignation()
    {
        return $this->hasMany(Resignation::class, 'updated_by');
    }
    public function addedNationality()
    {
        return $this->hasMany(Nationality::class, 'added_by');
    }
    public function updatedNationality()
    {
        return $this->hasMany(Nationality::class, 'updated_by');
    }
    public function addedReligion()
    {
        return $this->hasMany(Religion::class, 'added_by');
    }
    public function updatedReligion()
    {
        return $this->hasMany(Religion::class, 'updated_by');
    }
    public function addedEmployees()
    {
        return $this->hasMany(Employee::class, 'added_by');
    }
    public function updatedEmployees()
    {
        return $this->hasMany(Employee::class, 'updated_by');
    }
    public function addedCountries()
    {
        return $this->hasMany(Country::class, 'added_by');
    }
    public function updatedCountries()
    {
        return $this->hasMany(Country::class, 'updated_by');
    }
    public function addedGovernorates()
    {
        return $this->hasMany(Governorate::class, 'added_by');
    }
    public function updatedGovernorates()
    {
        return $this->hasMany(Governorate::class, 'updated_by');
    }
    public function addedCities()
    {
        return $this->hasMany(City::class, 'added_by');
    }
    public function updatedCities()
    {
        return $this->hasMany(City::class, 'updated_by');
    }
    public function addedBloodGroup()
    {
        return $this->hasMany(BloodGroup::class, 'added_by');
    }
    public function updatedBloodGroup()
    {
        return $this->hasMany(BloodGroup::class, 'updated_by');
    }
    public function addedFiles()
    {
        return $this->hasMany(File::class, 'added_by');
    }
    public function updatedFiles()
    {
        return $this->hasMany(File::class, 'updated_by');
    }
    public function addedAllowanceTypes()
    {
        return $this->hasMany(AllowanceType::class, 'added_by');
    }
    public function updatedAllowanceTypes()
    {
        return $this->hasMany(AllowanceType::class, 'updated_by');
    }
    public function addedDeductionTypes()
    {
        return $this->hasMany(DeductionType::class, 'added_by');
    }
    public function updatedDeductionTypes()
    {
        return $this->hasMany(DeductionType::class, 'updated_by');
    }
    public function addedBonuses(){
        return $this->hasMany(Bonus::class, 'added_by');
    }
    public function updatedBonuses(){
        return $this->hasMany(Bonus::class, 'updated_by');
    }
    public function addedMainSalaryEmployees(){
        return $this->hasMany(MainSalaryEmployee::class, 'added_by');
    }
    public function updatedMainSalaryEmployees(){
        return $this->hasMany(MainSalaryEmployee::class, 'updated_by');
    }
    public function addedMainSalaryEmployeeDeductions(){
        return $this->hasMany(MainSalaryEmployeeDeduction::class, 'added_by');
    }
    public function updatedMainSalaryEmployeeDeductions(){
        return $this->hasMany(MainSalaryEmployeeDeduction::class, 'updated_by');
    }
    public function archivedMainSalaryEmployeeDeductions(){
        return $this->hasMany(MainSalaryEmployeeDeduction::class, 'archived_by');
    }
    public function addedMainSalaryEmployeeAllowances(){
        return $this->hasMany(MainSalaryEmployeeAllowance::class, 'added_by');
    }
    public function updatedMainSalaryEmployeeAllowances(){
        return $this->hasMany(MainSalaryEmployeeAllowance::class, 'updated_by');
    }
    public function archivedMainSalaryEmployeeAllowances(){
        return $this->hasMany(MainSalaryEmployeeAllowance::class, 'archived_by');
    }
        public function addedMainSalaryEmployeeAbsences(){
        return $this->hasMany(MainSalaryEmployeeAbsence::class, 'added_by');
    }
    public function updatedMainSalaryEmployeeAbsences(){
        return $this->hasMany(MainSalaryEmployeeAbsence::class, 'updated_by');
    }
    public function addedMainSalaryEmployeeDeductionTypes()
    {
        return $this->hasMany(MainSalaryEmployeeDeductionType::class, 'added_by');
    }

    public function updatedMainSalaryEmployeeDeductionTypes()
    {
        return $this->hasMany(MainSalaryEmployeeDeductionType::class, 'updated_by');
    }

    public function archivedMainSalaryEmployeeDeductionTypes()
    {
        return $this->hasMany(MainSalaryEmployeeDeductionType::class, 'archived_by');
    }
    public function addedMainSalaryEmployeeLoans()
    {
        return $this->hasMany(MainSalaryEmployeeLoan::class, 'added_by');
    }
    public function updatedMainSalaryEmployeeLoans()
    {
        return $this->hasMany(MainSalaryEmployeeLoan::class, 'updated_by');
    }
    public function archivedMainSalaryEmployeeLoans()
    {
        return $this->hasMany(MainSalaryEmployeeLoan::class, 'archived_by');
    }
    public function addedMainSalaryEmployeeBonuses()
    {
        return $this->hasMany(MainSalaryEmployeeBonus::class, 'added_by');
    }
    public function updatedMainSalaryEmployeeBonuses()
    {
        return $this->hasMany(MainSalaryEmployeeBonus::class, 'updated_by');
    }
    public function archivedMainSalaryEmployeeBonuses()
    {
        return $this->hasMany(MainSalaryEmployeeBonus::class, 'archived_by');
    }
    public function addedMainSalaryEmployeeAdditions()
    {
        return $this->hasMany(MainSalaryEmployeeAddition::class, 'added_by');
    }
    public function updatedMainSalaryEmployeeAdditions()
    {
        return $this->hasMany(MainSalaryEmployeeAddition::class, 'updated_by');
    }
    public function archivedMainSalaryEmployeeAdditions()
    {
        return $this->hasMany(MainSalaryEmployeeAddition::class, 'archived_by');
    }
    public function addedMainSalaryEmployeePLoans()
    {
        return $this->hasMany(MainSalaryEmployeePLoan::class, 'added_by');
    }
    public function updatedMainSalaryEmployeePLoans()
    {
        return $this->hasMany(MainSalaryEmployeePLoan::class, 'updated_by');
    }
    public function archivedMainSalaryEmployeePLoans()
    {
        return $this->hasMany(MainSalaryEmployeePLoan::class, 'archived_by');
    }
    public function disbursedMainSalaryEmployeePLoans()
    {
        return $this->hasMany(MainSalaryEmployeePLoan::class, 'disbursed_by');
    }
    public function addedMainSalaryEmployeePLoanInstallments()
    {
        return $this->hasMany(MainSalaryEmployeePLoanInstallment::class, 'added_by');
    }
    public function updatedMainSalaryEmployeePLoanInstallments()
    {
        return $this->hasMany(MainSalaryEmployeePLoanInstallment::class, 'updated_by');
    }
    public function archivedMainSalaryEmployeePLoanInstallments()
    {
        return $this->hasMany(MainSalaryEmployeePLoanInstallment::class, 'archived_by');
    }
    public function employeeFixedAllowances()
    {
        return $this->hasMany(EmployeeFixedAllowance::class, 'added_by');
    }
    public function updatedEmployeeFixedAllowances()
    {
        return $this->hasMany(EmployeeFixedAllowance::class, 'updated_by');
    }
    public function addedEmployeeSalaryArchives()
    {
        return $this->hasMany(EmployeeSalaryArchive::class, 'added_by');
    }
    public function updatedEmployeeSalaryArchives()
    {
        return $this->hasMany(EmployeeSalaryArchive::class, 'updated_by');
    }
    public function addedAttendanceDepartureActionsExcel()
    {
        return $this->hasMany(AttendanceDepartureActionsExcel::class, 'added_by');
    }
    public function addedAttendancesDepartures()
    {
        return $this->hasMany(AttendanceDeparture::class, 'added_by');
    }
    public function updatedAttendancesDepartures()
    {
        return $this->hasMany(AttendanceDeparture::class, 'updated_by');
    }
    public function archivedAttendancesDepartures()
    {
        return $this->hasMany(AttendanceDeparture::class, 'archived_by');
    }
    public function addedAttendancesDeparturesActions()
    {
        return $this->hasMany(AttendanceDepartureAction::class, 'added_by');
    }
    public function updatedAttendancesDeparturesActions()
    {
        return $this->hasMany(AttendanceDepartureAction::class, 'updated_by');
    }
}
