<?php

namespace App\Filament\Admin\Resources\Materials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Material Information')
                    ->schema([
                        Select::make('supplier_id')
                            ->relationship('supplier', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')->required(),
                                TextInput::make('phone'),
                                TextInput::make('email')->email(),
                            ]),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Oak Wood, Peinture Blanche'),

                        TextInput::make('unit_price')
                            ->required()
                            ->numeric()
                            ->prefix('MAD')
                            ->step(0.01)
                            ->placeholder('100.00'),

                        Select::make('unit')
                            ->required()
                            ->options([
                                'meter' => 'Meter (m)',
                                'kg' => 'Kilogram (kg)',
                                'liter' => 'Liter (L)',
                                'piece' => 'Piece (pc)',
                                'box' => 'Box',
                                'bag' => 'Bag',
                            ])
                            ->default('piece'),
                    ])
                    ->columns(2),
            ]);
    }
}
