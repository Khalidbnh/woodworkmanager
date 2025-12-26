<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>
        {{ $invoice->isDevis() ? 'Devis' : 'Facture' }} {{ $invoice->invoice_number }}
    </title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            margin-bottom: 20px;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
        }
        .section {
            margin-top: 15px;
        }
        .total {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="title">
        {{ strtoupper($invoice->type) }}
    </div>
    <div>
        Number: {{ $invoice->invoice_number }} <br>
        Issued: {{ $invoice->issued_date?->format('d/m/Y') }}
    </div>
</div>

<div class="section">
    <strong>Project:</strong> {{ $invoice->project->name }} <br>
    <strong>Status:</strong> {{ ucfirst($invoice->status) }}
</div>

@if($invoice->isDevis())
    <div style="position: fixed; top: 40%; left: 20%; opacity: 0.1; font-size: 80px;">
        DEVIS
    </div>
    <div class="section">
        <strong>Valid until:</strong>
        {{ $invoice->valid_until?->format('d/m/Y') }}
    </div>
@endif

@if($invoice->isFacture())
    <div class="section">
        <strong>Due date:</strong>
        {{ $invoice->due_date?->format('d/m/Y') }}
    </div>
@endif

<div class="section total">
    Total: {{ number_format($invoice->amount, 2) }} MAD
</div>

@if($invoice->notes)
    <div class="section">
        <strong>Notes:</strong><br>
        {!! nl2br(e($invoice->notes)) !!}
    </div>
@endif

</body>
</html>
