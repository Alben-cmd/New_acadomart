<?php

namespace App\Filament\Admin\Resources\Challenges;

use App\Filament\Admin\Resources\Challenges\Pages\CreateChallenge;
use App\Filament\Admin\Resources\Challenges\Pages\EditChallenge;
use App\Filament\Admin\Resources\Challenges\Pages\ListChallenges;
use App\Filament\Admin\Resources\Challenges\Pages\ViewChallenge;
use App\Filament\Admin\Resources\Challenges\Schemas\ChallengeForm;
use App\Filament\Admin\Resources\Challenges\Schemas\ChallengeInfolist;
use App\Filament\Admin\Resources\Challenges\Tables\ChallengesTable;
use App\Models\Challenge;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChallengeResource extends Resource
{
    protected static ?string $model = Challenge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ChallengeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChallengeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChallengesTable::configure($table);
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
            'index' => ListChallenges::route('/'),
            'create' => CreateChallenge::route('/create'),
            'view' => ViewChallenge::route('/{record}'),
            'edit' => EditChallenge::route('/{record}/edit'),
        ];
    }
}
