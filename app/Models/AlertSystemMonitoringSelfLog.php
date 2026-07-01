<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class AlertSystemMonitoringSelfLog extends Model
{
    protected $table = 'alert_system_monitoring_self_logs';

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
