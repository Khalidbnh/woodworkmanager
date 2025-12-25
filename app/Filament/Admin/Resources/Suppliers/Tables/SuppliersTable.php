<?php

namespace App\Filament\Admin\Resources\Suppliers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('current_debt')
                    ->label('Manual Debt')
                    ->money('MAD')
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->weight(fn ($state) => $state > 0 ? 'bold' : 'normal'),

                TextColumn::make('materials_count')
                    ->counts('materials')
                    ->label('Materials')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Filter::make('has_debt')
                    ->query(fn ($query) => $query->where('current_debt', '>', 0))
                    ->label('Has Debt')
                    ->toggle(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('current_debt', 'desc');
    }
}
