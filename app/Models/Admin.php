<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([ 'name', 'email', 'username', 'password', 'added_by', 'updated_by', 'status', 'date', 'created_at', 'updated_at', 'company_id'])]
#[Hidden(['password', 'remember_token'])]
class Admin extends User
{
     use HasFactory;
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
