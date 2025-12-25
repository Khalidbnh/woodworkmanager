<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use App\Models\Employee;
use App\Models\Material;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project Information')
                    ->description('Basic project details')
                    ->schema([
                        Select::make('client_id')
                            ->label('Client')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('phone'),
                                TextInput::make('email')->email(),
                                Textarea::make('address'),
                            ])
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->label('Project Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Kitchen Renovation'),

                        TextInput::make('site_address')
                            ->label('Work Site Address')
                            ->maxLength(255)
                            ->placeholder('Where the work will be done')
                            ->helperText('Can be different from client address'),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Project details, requirements, specifications...'),
                    ])
                    ->columns(2),

                Section::make('Timeline')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->native(false),

                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->native(false)
                            ->after('start_date'),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                            ])
                            ->default('pending')
                            ->required(),
                    ])
                    ->columns(3),

                Section::make('Pricing')
                    ->description('Manual price or calculated from materials')
                    ->schema([
                        TextInput::make('estimated_price')
                            ->label('Estimated Price (MAD)')
                            ->numeric()
                            ->prefix('MAD')
                            ->step(0.01)
                            ->placeholder('15000.00')
                            ->helperText('Optional: Enter manual price if not calculating from materials'),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->placeholder('Additional notes, special requirements, etc.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Team Assignment')
                    ->description('Assign employees to this project')
                    ->schema([
                        CheckboxList::make('employees')
                            ->relationship('employees', 'name')
                            ->label('Select Employees')
                            ->columns(2)
                            ->helperText('Select employees assigned to this project'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Materials & Costs')
                    ->description('Add materials used in this project and track payments')
                    ->schema([
                        Repeater::make('materials')
                            ->relationship()
                            ->schema([
                                Select::make('material_id')
                                    ->label('Material')
                                    ->options(function () {
                                        return Material::with('supplier')
                                            ->get()
                                            ->mapWithKeys(fn ($material) => [
                                                $material->id => "{$material->name} ({$material->supplier->name}) - {$material->unit_price} MAD/{$material->unit}"
                                            ]);
                                    })
                                    ->searchable()
                                    ->required()

                                    ->createOptionForm([
                                        Select::make('supplier_id')
                                            ->label('Supplier')
                                            ->options(\App\Models\Supplier::pluck('name', 'id'))
                                            ->required(),

                                        TextInput::make('name')
                                            ->required(),

                                        TextInput::make('unit_price')
                                            ->numeric()
                                            ->required()
                                            ->prefix('MAD'),

                                        Select::make('unit')
                                            ->options([
                                                'meter' => 'Meter',
                                                'kg' => 'Kilogram',
                                                'liter' => 'Liter',
                                                'piece' => 'Piece',
                                                'box' => 'Box',
                                            ])
                                            ->required(),
                                    ])

                                    // 👇 REQUIRED INSIDE REPEATER
                                    ->createOptionUsing(function (array $data) {
                                        return Material::create($data)->id;
                                    })

                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if ($state) {
                                            $material = Material::find($state);
                                            $set('unit_price', $material?->unit_price);
                                        }
                                    })
                                    ->columnSpan(3),

                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $unitPrice = floatval($get('unit_price') ?? 0);
                                        $quantity = floatval($state ?? 0);
                                        $totalCost = $quantity * $unitPrice;
                                        $set('total_cost', number_format($totalCost, 2, '.', ''));

                                        $amountPaid = floatval($get('amount_paid') ?? 0);
                                        $remaining = $totalCost - $amountPaid;
                                        $set('amount_remaining', number_format($remaining, 2, '.', ''));

                                        // Auto-set status
                                        if ($amountPaid >= $totalCost) {
                                            $set('payment_status', 'paid');
                                        } elseif ($amountPaid > 0) {
                                            $set('payment_status', 'partial');
                                        } else {
                                            $set('payment_status', 'unpaid');
                                        }
                                    })
                                    ->columnSpan(1),

                                TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('MAD')
                                    ->step(0.01)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $quantity = floatval($get('quantity') ?? 0);
                                        $unitPrice = floatval($state ?? 0);
                                        $totalCost = $quantity * $unitPrice;
                                        $set('total_cost', number_format($totalCost, 2, '.', ''));

                                        $amountPaid = floatval($get('amount_paid') ?? 0);
                                        $remaining = $totalCost - $amountPaid;
                                        $set('amount_remaining', number_format($remaining, 2, '.', ''));
                                    })
                                    ->columnSpan(1),

                                Placeholder::make('total_cost_display')
                                    ->label('Total Cost')
                                    ->content(fn (Get $get) => number_format(floatval($get('total_cost') ?? 0), 2) . ' MAD')
                                    ->columnSpan(1),

                                TextInput::make('total_cost')
                                    ->label('Total Cost')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->hidden(),

                                TextInput::make('amount_paid')
                                    ->label('Amount Paid')
                                    ->numeric()
                                    ->prefix('MAD')
                                    ->step(0.01)
                                    ->default(0)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $totalCost = floatval($get('total_cost') ?? 0);
                                        $amountPaid = floatval($state ?? 0);
                                        $remaining = $totalCost - $amountPaid;
                                        $set('amount_remaining', number_format($remaining, 2, '.', ''));

                                        // Auto-set status
                                        if ($amountPaid >= $totalCost) {
                                            $set('payment_status', 'paid');
                                        } elseif ($amountPaid > 0) {
                                            $set('payment_status', 'partial');
                                        } else {
                                            $set('payment_status', 'unpaid');
                                        }
                                    })
                                    ->columnSpan(1),

                                Placeholder::make('amount_remaining_display')
                                    ->label('Remaining')
                                    ->content(fn (Get $get) => number_format(floatval($get('amount_remaining') ?? 0), 2) . ' MAD')
                                    ->columnSpan(1),

                                TextInput::make('amount_remaining')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->hidden(),

                                Select::make('payment_status')
                                    ->label('Payment Status')
                                    ->options([
                                        'unpaid' => 'Unpaid',
                                        'partial' => 'Partial',
                                        'paid' => 'Paid',
                                    ])
                                    ->required()
                                    ->default('unpaid')
                                    ->columnSpan(1),

                                DatePicker::make('purchase_date')
                                    ->label('Purchase Date')
                                    ->default(now())
                                    ->native(false)
                                    ->columnSpan(1),

                                DatePicker::make('paid_date')
                                    ->label('Paid Date')
                                    ->native(false)
                                    ->visible(fn (Get $get) => $get('payment_status') === 'paid')
                                    ->columnSpan(1),

                                Textarea::make('notes')
                                    ->label('Notes')
                                    ->rows(2)
                                    ->columnSpanFull()
                                    ->placeholder('Delivery notes, quality, etc.'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Material')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                            isset($state['material_id']) && Material::find($state['material_id'])
                                ? Material::find($state['material_id'])->name . ' - ' . ($state['quantity'] ?? '0') . ' units'
                                : null
                            )
                            ->cloneable()
                            ->reorderableWithButtons(),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($record) => $record !== null),
            ]);
    }
}
