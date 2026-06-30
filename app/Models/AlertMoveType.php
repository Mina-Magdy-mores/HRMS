<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $alert_module_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property string|null $notes
 * @property int $is_active
 * @property int $company_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \App\Models\Admin|null $updatedBy
 * @property-read \App\Models\AlertModule $alertModule
 */
#[Guarded([])]
class AlertMoveType extends Model
{
    protected $table = 'alert_move_types';

    public function alertModule()
    {
        return $this->belongsTo(AlertModule::class, 'alert_module_id');
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
