<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'role',
    ];


    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'employee_project')
            ->withPivot('assigned_from', 'assigned_to')
            ->withTimestamps();
    }
}
