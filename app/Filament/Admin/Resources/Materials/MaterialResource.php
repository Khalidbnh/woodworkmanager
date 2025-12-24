<?php

namespace App\Filament\Admin\Resources\Materials;

use App\Filament\Admin\Resources\Materials\Pages\CreateMaterial;
use App\Filament\Admin\Resources\Materials\Pages\EditMaterial;
use App\Filament\Admin\Resources\Materials\Pages\ListMaterials;
use App\Filament\Admin\Resources\Materials\Schemas\MaterialForm;
use App\Filament\Admin\Resources\Materials\Tables\MaterialsTable;
use App\Models\Material;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Materials';

    protected static ?string $recordTitleAttribute = 'MaterialAttribute';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return MaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMaterials::route('/'),
            'create' => CreateMaterial::route('/create'),
            'edit' => EditMaterial::route('/{record}/edit'),
        ];
    }
}
