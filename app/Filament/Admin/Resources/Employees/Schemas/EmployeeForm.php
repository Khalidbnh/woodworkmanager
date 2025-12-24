<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
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
                            ->placeholder('e.g., Khalid Hassan'),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(255)
                            ->placeholder('+212 6 XX XX XX XX'),

                        TextInput::make('role')
                            ->label('Role / Position')
                            ->maxLength(255)
                            ->placeholder('e.g., Carpenter, Painter, Chef de Chantier')
                            ->helperText('Job title or specialty'),
                    ])
                    ->columns(2),
            ]);
    }
}
