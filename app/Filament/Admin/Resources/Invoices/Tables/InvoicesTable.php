<?php

namespace App\Filament\Admin\Resources\Invoices\Tables;

use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'devis' => 'info',
                        'facture' => 'success',
                    }),

                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('project.client.name')
                    ->label('Client')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('amount')
                    ->money('MAD')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'paid' => 'success',
                        'unpaid' => 'warning',
                        'overdue' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('issued_date')
                    ->label('Issued')
                    ->date()
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'devis' => 'Devis',
                        'facture' => 'Facture',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                        'overdue' => 'Overdue',
                    ]),
            ])
            ->recordActions([
                Action::make('convert_to_facture')
                    ->label('Convert to Facture')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success')
                    ->visible(fn (Invoice $record) => $record->canConvertToFacture())
                    ->action(function (Invoice $record) {
                        $facture = $record->convertToFacture();

                        Notification::make()
                            ->success()
                            ->title('Facture Created')
                            ->body("Facture {$facture->invoice_number} created successfully!")
                            ->send();
                    })
                    ->requiresConfirmation(),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('issued_date', 'desc');
    }
}
