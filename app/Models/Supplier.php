<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'current_debt',
        'debt_notes',
    ];

    protected $casts = [
        'current_debt' => 'decimal:2',
    ];

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    // Helper: Calculate total debt from unpaid materials
    public function calculateMaterialDebt(): float
    {
        return $this->materials()
            ->with('projects')
            ->get()
            ->sum(function ($material) {
                return $material->projects->sum('pivot.amount_remaining');
            });
    }

    // Helper: Get total debt (manual + calculated)
    public function getTotalDebt(): float
    {
        return $this->current_debt + $this->calculateMaterialDebt();
    }
}
