<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class EmployeeMessage extends Model
{
    use LogsActivity;

    protected $table = 'employee_messages';

    protected $fillable = [
        'company_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
    ];

    public function sender()
    {
        return $this->belongsTo(Admin::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Admin::class, 'receiver_id');
    }
}
