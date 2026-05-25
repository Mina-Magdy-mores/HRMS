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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Department> $addedDepartments
 * @property-read int|null $added_departments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceCalendar> $addedFinanceCalendars
 * @property-read int|null $added_finance_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceMonthlyCalendar> $addedFinanceMonthlyCalendars
 * @property-read int|null $added_finance_monthly_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobsCategory> $addedJobsCategoies
 * @property-read int|null $added_jobs_categoies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Occasion> $addedOccasions
 * @property-read int|null $added_occasions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Qualification> $addedQualifications
 * @property-read int|null $added_qualifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShiftsType> $addedShiftsTypes
 * @property-read int|null $added_shifts_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Branche> $branches
 * @property-read int|null $branches_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdminPanelSetting> $updatedAdminPanels
 * @property-read int|null $updated_admin_panels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Branche> $updatedBranches
 * @property-read int|null $updated_branches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Department> $updatedDepartments
 * @property-read int|null $updated_departments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceCalendar> $updatedFinanceCalendars
 * @property-read int|null $updated_finance_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinanceMonthlyCalendar> $updatedFinanceMonthlyCalendars
 * @property-read int|null $updated_finance_monthly_calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobsCategory> $updatedJobsCategoies
 * @property-read int|null $updated_jobs_categoies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Occasion> $updatedOccasions
 * @property-read int|null $updated_occasions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Qualification> $updatedQualifications
 * @property-read int|null $updated_qualifications_count
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
#[Fillable(['name', 'email', 'username', 'password', 'added_by', 'updated_by', 'status', 'date', 'created_at', 'updated_at', 'company_id'])]
#[Hidden(['password', 'remember_token'])]
class Admin extends User
{
    use HasFactory;
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
}
