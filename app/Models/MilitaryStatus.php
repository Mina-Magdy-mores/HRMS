<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class MilitaryStatus extends Model
{
    public function employees()
    {
        return $this->hasMany(Employee::class, 'military_status_id');
    }
}
