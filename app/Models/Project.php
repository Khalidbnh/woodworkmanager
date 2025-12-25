<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'description',
        'site_address',
        'start_date',
        'end_date',
        'status',
        'estimated_price',
        'total_material_cost',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'estimated_price' => 'decimal:2',
        'total_material_cost' => 'decimal:2',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_project')
            ->withPivot('assigned_from', 'assigned_to')
            ->withTimestamps();
    }

    public function projectMaterials(): HasMany
    {
        return $this->hasMany(ProjectMaterial::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Recalculate project material cost
    public function recalculateMaterialCost(): void
    {
        $this->updateQuietly([
            'total_material_cost' =>
                $this->projectMaterials()->sum('total_cost'),
        ]);
    }

    // Final price logic
    public function getFinalPrice(): float
    {
        return $this->estimated_price ?? $this->total_material_cost;
    }
}
