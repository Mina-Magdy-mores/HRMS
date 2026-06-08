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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MilitaryStatus whereUpdatedBy($value)
 * @mixin \Eloquent
 */
#[Guarded([])]
class MilitaryStatus extends Model
{
    public function employees()
    {
        return $this->hasMany(Employee::class, 'military_status_id');
    }
}
