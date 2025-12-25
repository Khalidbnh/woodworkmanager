<?php

namespace App\Filament\Admin\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Supplier Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Bois Maroc'),

                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('+212 5 XX XX XX XX'),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        Textarea::make('address')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Debt Tracking')
                    ->description('Track manual debts or old balances')
                    ->schema([
                        TextInput::make('current_debt')
                            ->label('Current Debt (MAD)')
                            ->numeric()
                            ->prefix('MAD')
                            ->step(0.01)
                            ->default(0)
                            ->helperText('Manual debt tracking (e.g., old unpaid invoices)')
                            ->placeholder('5000.00'),

                        Textarea::make('debt_notes')
                            ->label('Debt Notes')
                            ->rows(2)
                            ->placeholder('e.g., Old debt from June 2024, payment plan agreed...')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
