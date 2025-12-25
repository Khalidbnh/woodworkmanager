<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'project_id',
        'type',
        'invoice_number',
        'amount',
        'status',
        'issued_date',
        'due_date',
        'valid_until',
        'paid_date',
        'notes',
        'converted_to_facture_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issued_date' => 'date',
        'due_date' => 'date',
        'valid_until' => 'date',
        'paid_date' => 'date',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function convertedToFacture(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'converted_to_facture_id');
    }

    // Scopes
    public function scopeDevis($query)
    {
        return $query->where('type', 'devis');
    }

    public function scopeFactures($query)
    {
        return $query->where('type', 'facture');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('type', 'facture')
            ->whereIn('status', ['unpaid', 'overdue']);
    }

    // Helper methods
    public function isDevis(): bool
    {
        return $this->type === 'devis';
    }

    public function isFacture(): bool
    {
        return $this->type === 'facture';
    }

    public function canConvertToFacture(): bool
    {
        return $this->isDevis()
            && $this->status === 'accepted'
            && !$this->converted_to_facture_id;
    }

    // Convert Devis to Facture
    public function convertToFacture(): self
    {
        if (!$this->canConvertToFacture()) {
            throw new \Exception('Cannot convert this Devis to Facture');
        }

        $facture = self::create([
            'project_id' => $this->project_id,
            'type' => 'facture',
            'invoice_number' => $this->generateFactureNumber(),
            'amount' => $this->amount,
            'status' => 'unpaid',
            'issued_date' => now(),
            'due_date' => now()->addDays(30),
            'notes' => "Basée sur le devis {$this->invoice_number}",
        ]);

        $this->update(['converted_to_facture_id' => $facture->id]);

        return $facture;
    }

    // Generate invoice number
    public static function generateInvoiceNumber(string $type): string
    {
        $prefix = $type === 'devis' ? 'DEV' : 'FACT';
        $year = date('Y');

        $lastInvoice = self::where('type', $type)
            ->where('invoice_number', 'LIKE', "{$prefix}-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastInvoice
            ? ((int) substr($lastInvoice->invoice_number, -3)) + 1
            : 1;

        return sprintf("%s-%s-%03d", $prefix, $year, $nextNumber);
    }

    private function generateFactureNumber(): string
    {
        return self::generateInvoiceNumber('facture');
    }

    // Boot method to auto-generate invoice number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (!$invoice->invoice_number) {
                $invoice->invoice_number = self::generateInvoiceNumber($invoice->type);
            }
        });
    }
}
