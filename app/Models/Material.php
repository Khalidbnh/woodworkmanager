<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $fillable = [
        'supplier_id',
        'name',
        'unit_price',
        'unit',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_material')
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
}
