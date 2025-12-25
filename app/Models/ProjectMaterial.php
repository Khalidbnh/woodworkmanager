<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMaterial extends Model
{
    protected $table = 'project_material';

    protected $fillable = [
        'project_id',
        'material_id',
        'quantity',
        'unit_price',
        'total_cost',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }



    protected static function booted()
    {
        static::saving(function (ProjectMaterial $pm) {
            $pm->total_cost = $pm->quantity * $pm->unit_price;
        });

        static::saved(fn ($pm) => $pm->project->recalculateMaterialCost());
        static::deleted(fn ($pm) => $pm->project->recalculateMaterialCost());
    }
}
