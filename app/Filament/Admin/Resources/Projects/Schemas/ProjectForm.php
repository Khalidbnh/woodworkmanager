<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
            ]);
    }
}
