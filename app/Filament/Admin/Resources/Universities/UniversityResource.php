<?php

namespace App\Filament\Admin\Resources\Universities;

use App\Filament\Admin\Resources\Universities\Pages\CreateUniversity;
use App\Filament\Admin\Resources\Universities\Pages\EditUniversity;
use App\Filament\Admin\Resources\Universities\Pages\ListUniversities;
use App\Filament\Admin\Resources\Universities\Pages\ViewUniversity;
use App\Filament\Admin\Resources\Universities\Schemas\UniversityForm;
use App\Filament\Admin\Resources\Universities\Schemas\UniversityInfolist;
use App\Filament\Admin\Resources\Universities\Tables\UniversitiesTable;
use App\Models\University;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UniversityResource extends Resource
{
    protected static ?string $model = University::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return UniversityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UniversityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UniversitiesTable::configure($table);
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
            'index' => ListUniversities::route('/'),
            'create' => CreateUniversity::route('/create'),
            'view' => ViewUniversity::route('/{record}'),
            'edit' => EditUniversity::route('/{record}/edit'),
        ];
    }
}
