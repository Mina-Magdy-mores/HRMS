<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class AdminArchive extends Model
{
    public function archivedBy()
    {
        return $this->belongsTo(Admin::class, 'archived_by');
    }
}
