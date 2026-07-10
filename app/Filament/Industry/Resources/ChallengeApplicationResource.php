<?php

namespace App\Filament\Industry\Resources;

use App\Models\ChallengeApplication;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Industry\Resources\ChallengeApplicationResource\Pages\ViewChallengeApplication;

class ChallengeApplicationResource extends Resource
{
    protected static ?string $model = ChallengeApplication::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Application Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('Student Name')
                                    ->weight('bold'),
                                TextEntry::make('user.email')
                                    ->label('Student Email'),
                                TextEntry::make('challenge.title')
                                    ->label('Opportunity/Challenge'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'gray',
                                        'reviewing' => 'warning',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                    }),
                                TextEntry::make('submission_url')
                                    ->label('Submission URL')
                                    ->url(fn ($state) => $state)
                                    ->openUrlInNewTab(),
                                TextEntry::make('submitted_at')
                                    ->dateTime(),
                                TextEntry::make('cover_letter')
                                    ->label('Cover Letter / Submission Notes')
                                    ->columnSpan(2),
                            ]),
                    ]),
            ]);
    }

    public static function getIndexUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null, bool $shouldGuessMissingParameters = false): string
    {
        return ManageChallengesResource::getUrl('index', panel: $panel);
    }

    public static function getPages(): array
    {
        return [
            'view' => ViewChallengeApplication::route('/{record}'),
        ];
    }
}
