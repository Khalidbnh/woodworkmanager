<?php

namespace App\Filament\Admin\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->schema([
                Section::make('Invoice Information')
                    ->schema([
                        Select::make('project_id')
                            ->label('Project')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->required()
                            ->columnSpan(2),

                        Select::make('type')
                            ->label('Type')
                            ->options([
                                'devis' => 'Devis (Quote)',
                                'facture' => 'Facture (Invoice)',
                            ])
                            ->required()
                            ->reactive()
                            ->default('devis')
                            ->columnSpan(3),

                        TextInput::make('invoice_number')
                            ->label('Invoice Number')
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Auto-generated on save')
                            ->columnSpan(1),

                        TextInput::make('amount')
                            ->label('Amount (MAD)')
                            ->required()
                            ->numeric()
                            ->prefix('MAD')
                            ->step(0.01)
                            ->columnSpan(2),

                        Select::make('status')
                            ->label('Status')
                            ->options(fn (Get $get) =>
                            $get('type') === 'devis'
                                ? [
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                            ]
                                : [
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'unpaid' => 'Unpaid',
                                'paid' => 'Paid',
                                'overdue' => 'Overdue',
                            ]
                            )
                            ->required()
                            ->default('draft')
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Section::make('Dates')
                    ->schema([
                        DatePicker::make('issued_date')
                            ->label('Issued Date')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->columnSpan(2),

                        DatePicker::make('valid_until')
                            ->label('Valid Until')
                            ->visible(fn (Get $get) => $get('type') === 'devis')
                            ->native(false)
                            ->helperText('For Devis only')
                            ->columnSpan(2),

                        DatePicker::make('due_date')
                            ->label('Due Date')
                            ->visible(fn (Get $get) => $get('type') === 'facture')
                            ->native(false)
                            ->after('issued_date')
                            ->helperText('For Factures only')
                            ->columnSpan(2),

                        DatePicker::make('paid_date')
                            ->label('Paid Date')
                            ->visible(fn (Get $get) => $get('type') === 'facture' && $get('status') === 'paid')
                            ->native(false)
                            ->helperText('When payment received')
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Terms, conditions, payment details...'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
