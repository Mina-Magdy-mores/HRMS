<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $company_id
 * @property int $country_id
 * @property int $added_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $addedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\City> $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\Country $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereUpdatedBy($value)
 * @mixin \Eloquent
 */
use App\Traits\LogsActivity;

class Governorate extends Model
{
    use LogsActivity;
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'governorate_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'governorate_id');
    }
}
