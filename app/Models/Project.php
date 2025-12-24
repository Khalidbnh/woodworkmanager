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
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'estimated_price' => 'decimal:2',
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

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'project_material')
            ->withPivot([
                'quantity',
                'unit_price',
                'total_cost',
                'amount_paid',
                'amount_remaining',
                'payment_status',
                'purchase_date',
                'paid_date',
                'notes'
            ])
            ->withTimestamps();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Helper method: Calculate total material cost
    public function calculateMaterialCost(): float
    {
        return $this->materials()->sum('project_material.total_cost') ?? 0;
    }

    // Helper method: Get final project price
    public function getFinalPrice(): float
    {
        // If manual price exists, use it
        if ($this->estimated_price) {
            return $this->estimated_price;
        }

        // Otherwise, calculate from materials
        return $this->calculateMaterialCost();
    }
}
