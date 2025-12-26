<?php

namespace App\Filament\Admin\Resources\Clients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;


class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Information')
                    ->description('Employee personal details')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter Client Name'),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(255)
                            ->placeholder('+212 6 XX XX XX XX'),

                        TextInput::make('email')
                            ->label('Email')
                            -> email()
                            ->required()
                            -> maxLength('50')
                            ->placeholder('client@example.com'),
                        Textarea::make('address')
                            ->label('Address')
                            -> maxLength('255')
                            ->placeholder('Client address')
                            -> columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
