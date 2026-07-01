<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereUpdatedBy($value)
 * @mixin \Eloquent
 */
use App\Traits\LogsActivity;

#[Guarded([])]
class Religion extends Model
{
    use LogsActivity;

    public function getModuleName()
    {
        return 'الأديان';
    }

    public function getLogName($actionName)
    {
        return "{$actionName} ديانة: {$this->name}";
    }

    public function getLogContent($actionName)
    {
        if ($actionName == 'تعديل') {
            $oldName = $this->getOriginal('name');
            return "تم تعديل اسم الديانة من: '{$oldName}' إلى: '{$this->name}'";
        }
        return "تم {$actionName} الديانة باسم: '{$this->name}'";
    }

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'religion_id');
    }
}
