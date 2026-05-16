<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
