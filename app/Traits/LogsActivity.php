<?php

namespace App\Traits;

use App\Models\AlertModule;
use App\Models\AlertMoveType;
use App\Models\AlertSystemMonitoring;
use App\Models\AdminPanelSetting;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logAction($model, 'إضافة');
        });

        static::updated(function ($model) {
            self::logAction($model, 'تعديل');
        });

        static::deleted(function ($model) {
            self::logAction($model, 'حذف');
        });
    }

    protected static function logAction($model, $actionName)
    {
        try {
            $company_id = Auth::check() ? Auth::user()->company_id : 1;
            $admin_id = Auth::check() ? Auth::id() : 1;

            // Check if system monitoring is active in settings
            $isActive = AdminPanelSetting::where('company_id', $company_id)->value('is_active_system_monitoring');
            if (!$isActive) {
                return;
            }

            // Get module name from model
            $moduleName = $model->getModuleName();
            if (!$moduleName) {
                return;
            }

            $module = AlertModule::where('name', $moduleName)->first();
            if (!$module) {
                return;
            }

            // Customize action name if model specifies it
            $actionName = method_exists($model, 'getLogActionName') ? $model->getLogActionName($actionName) : $actionName;

            $moveType = AlertMoveType::where([
                'name' => $actionName,
                'alert_module_id' => $module->id,
            ])->first();

            if (!$moveType) {
                return;
            }

            // Get log details from model methods
            $name = $model->getLogName($actionName);
            $content = $model->getLogContent($actionName);
            $employeeId = method_exists($model, 'getLogEmployeeId') ? $model->getLogEmployeeId() : null;

            AlertSystemMonitoring::create([
                'name' => $name,
                'content' => $content,
                'alert_module_id' => $module->id,
                'alert_move_type_id' => $moveType->id,
                'foreign_key_table_td' => $model->id,
                'employee_id' => $employeeId,
                'is_important' => 0,
                'is_active' => 1,
                'added_by' => $admin_id,
                'updated_by' => $admin_id,
                'company_id' => $company_id,
            ]);
        } catch (\Exception $e) {
            logger()->error('Error logging system monitoring activity: ' . $e->getMessage());
        }
    }

    /**
     * Get Module Name mapping
     */
    public function getModuleName()
    {
        $map = [
            \App\Models\AdminPanelSetting::class => 'الضبط العام',
            \App\Models\FinanceCalendar::class => 'السنوات المالية',
            \App\Models\FinanceMonthlyCalendar::class => 'السنوات المالية',
            \App\Models\Branche::class => 'الفروع',
            \App\Models\ShiftsType::class => 'أنواع الشفتات',
            \App\Models\Department::class => 'إدارات الموظفين',
            \App\Models\JobsCategory::class => 'تصنيفات الوظائف',
            \App\Models\Qualification::class => 'مؤهلات الموظفين',
            \App\Models\Occasion::class => 'المناسبات الرسمية',
            \App\Models\VacationType::class => 'أنواع الإجازات',
            \App\Models\Resignation::class => 'انواع استقالات الموظفين',
            \App\Models\Nationality::class => 'الجنسية',
            \App\Models\Religion::class => 'الأديان',
            \App\Models\BloodGroup::class => 'فصائل الدم',
            \App\Models\Country::class => 'الدول',
            \App\Models\Governorate::class => 'المحافظات',
            \App\Models\City::class => 'المدن',
            \App\Models\Employee::class => 'بيانات الموظفين',
            \App\Models\AllowanceType::class => 'انواع البدل للراتب',
            \App\Models\DeductionType::class => 'انواع الخصم للراتب',
            \App\Models\Bonus::class => 'انواع المكافآت للراتب',
            \App\Models\MainSalaryEmployee::class => 'بيانات رواتب الموظفين',
            \App\Models\MainSalaryEmployeeDeductionType::class => 'الجزاءات اليدويه',
            \App\Models\MainSalaryEmployeeAbsence::class => 'خصم الغياب اليدوي',
            \App\Models\MainSalaryEmployeeAddition::class => 'أضافه الأيام اليدوي',
            \App\Models\MainSalaryEmployeeDeduction::class => 'الخصومات المالية المسجلة',
            \App\Models\MainSalaryEmployeeBonus::class => 'المكافئات المالية المسجلة',
            \App\Models\MainSalaryEmployeeAllowance::class => 'البدلات المالية المسجلة',
            \App\Models\MainSalaryEmployeeLoan::class => 'السلف الشهرية',
            \App\Models\MainSalaryEmployeePLoan::class => 'السلف المستديمة',
            \App\Models\AttendanceDeparture::class => 'سجلات البصمات',
            \App\Models\MainEmployeesVacationsBalances::class => 'أرصدة إجازات الموظفين',
            \App\Models\Admin::class => 'بروفايل الادمين',
            \App\Models\MainEmployeeInvestigation::class => 'التحقيقات الإدارية',
            \App\Models\AlertSystemMonitoring::class => 'مراقبة النظام',
        ];

        return $map[static::class] ?? null;
    }

    /**
     * Default log title mapping
     */
    public function getLogName($actionName)
    {
        $nameField = $this->getLogDisplayNameField();
        $displayName = $nameField ? $this->$nameField : "رقم #{$this->id}";
        return "{$actionName} في {$this->getModuleName()}: {$displayName}";
    }

    /**
     * Default log content mapping showing exact field updates
     */
    public function getLogContent($actionName)
    {
        $module = $this->getModuleName();
        
        if ($actionName === 'حذف') {
            return "تم حذف السجل رقم #{$this->id} من قسم {$module}.";
        }

        if ($actionName === 'إضافة') {
            return "تم إضافة سجل جديد رقم #{$this->id} في قسم {$module}.";
        }

        // For updates and custom actions (like Month opening/archiving)
        $changes = [];
        foreach ($this->getDirty() as $key => $value) {
            if (in_array($key, ['updated_at', 'created_at'])) {
                continue;
            }
            $old = $this->getOriginal($key);
            $changes[] = "حقل ({$key}) من '{$old}' إلى '{$value}'";
        }
        
        if (empty($changes)) {
            return "تم {$actionName} السجل رقم #{$this->id} في قسم {$module} (لم تتغير قيم أساسية).";
        }
        return "تم {$actionName} السجل رقم #{$this->id} في قسم {$module}:\n" . implode("\n", $changes);
    }

    /**
     * Resolve standard display name field
     */
    protected function getLogDisplayNameField()
    {
        foreach (['name', 'title', 'name_ar', 'username', 'employee_code'] as $field) {
            if (isset($this->$field)) {
                return $field;
            }
        }
        return null;
    }

    public function getEmployeeName()
    {
        if (isset($this->employee_name) && !empty($this->employee_name)) {
            return $this->employee_name;
        }

        if (isset($this->employee_id) && !empty($this->employee_id)) {
            $name = \Illuminate\Support\Facades\DB::table('employees')
                ->where('id', $this->employee_id)
                ->value('name');
            if ($name) {
                return $name;
            }
        }

        return "غير معروف";
    }
}
