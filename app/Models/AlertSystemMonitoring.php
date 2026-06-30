<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $content
 * @property int $alert_module_id
 * @property int $alert_move_type_id
 * @property int|null $foreign_key_table_td
 * @property int|null $employee_id
 * @property int $is_important
 * @property int $is_active
 * @property int $added_by
 * @property int|null $updated_by
 * @property int $company_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $updatedBy
 * @property-read \App\Models\AlertModule $alertModule
 * @property-read \App\Models\AlertMoveType $alertMoveType
 * @property-read \App\Models\Employee|null $employee
 */
#[Guarded([])]
class AlertSystemMonitoring extends Model
{
    protected $table = 'alert_system_monitorings';

    public function alertModule()
    {
        return $this->belongsTo(AlertModule::class, 'alert_module_id');
    }

    public function alertMoveType()
    {
        return $this->belongsTo(AlertMoveType::class, 'alert_move_type_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
}
