<?php

namespace App\Filament\Admin\Resources\Invoices\Pages;

use App\Filament\Admin\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;


class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    /** @var Invoice $invoice */
                    $invoice = $this->record;

                    $pdf = Pdf::loadView('pdf.invoice', [
                        'invoice' => $invoice,
                    ]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $invoice->invoice_number . '.pdf'
                    );
                }),
            DeleteAction::make(),
        ];
    }
}
