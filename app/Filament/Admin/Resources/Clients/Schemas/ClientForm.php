<?php

namespace App\Filament\Admin\Resources\Clients\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;


class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    -> maxLength('50')
                    ->placeholder('Enter client name'),
                TextInput::make('phone')
                    ->label('Phone')
                    ->required()
                    -> numeric()
                    -> maxLength('12')
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
            ]);
    }
}
