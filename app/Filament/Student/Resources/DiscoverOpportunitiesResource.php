<?php

namespace App\Filament\Student\Resources;

use App\Models\Challenge;
use App\Models\ChallengeApplication;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class DiscoverOpportunitiesResource extends Resource
{
    protected static ?string $model = Challenge::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationLabel = 'Discover Opportunities';

    protected static ?string $pluralModelLabel = 'Opportunities & Challenges';

    protected static ?string $modelLabel = 'Opportunity';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 'active');
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('company.name')
                    ->label('Posted By'),
                TextInput::make('title'),
                Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
                Textarea::make('requirements')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('reward')
                    ->label('Reward/Incentive'),
                TextInput::make('deadline')
                    ->type('date'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Opportunity Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('company.name')
                                    ->label('Posted By')
                                    ->weight('semibold'),
                                TextEntry::make('title')
                                    ->weight('semibold'),
                                TextEntry::make('type')
                                    ->label('Type')
                                    ->badge()
                                    ->color('gray'),
                                TextEntry::make('location')
                                    ->label('Location'),
                                TextEntry::make('duration')
                                    ->label('Duration'),
                                TextEntry::make('reward')
                                    ->label('Reward/Incentive'),
                                TextEntry::make('deadline')
                                    ->date(),
                                TextEntry::make('skills.name')
                                    ->label('Required Skills')
                                    ->badge(),
                                TextEntry::make('description')
                                    ->columnSpanFull(),
                                TextEntry::make('requirements')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ViewColumn::make('opportunity_card')
                    ->label('')
                    ->view('filament.student.components.opportunity-card')
                    ->extraCellAttributes([
                        'style' => 'white-space: normal;',
                    ]),
                TextColumn::make('title')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('company.name')
                    ->searchable()
                    ->hidden(),
            ])
            ->contentGrid([
                'default' => 1,
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'Internship' => 'Internship',
                        'Full-time' => 'Full-time',
                        'Part-time' => 'Part-time',
                        'Contract' => 'Contract',
                    ])
                    ->placeholder('All Types'),
                SelectFilter::make('skills')
                    ->relationship('skills', 'name')
                    ->multiple()
                    ->preload(),
                Filter::make('hide_applied')
                    ->label('Hide Applied')
                    ->query(fn (Builder $query) => $query->whereDoesntHave('applications', function ($q) {
                        $q->where('user_id', Auth::id());
                    })),
                Filter::make('remote_only')
                    ->label('Remote Only')
                    ->query(fn (Builder $query) => $query->where('location', 'like', '%remote%')),
                Filter::make('active_only')
                    ->label('Active Only')
                    ->query(fn (Builder $query) => $query->where(function ($q) {
                        $q->whereNull('deadline')
                            ->orWhere('deadline', '>=', now()->toDateString());
                    })),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                
                \Filament\Actions\Action::make('apply')
                    ->label('Apply/Submit')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->hidden(fn (Challenge $record) => $record->applications()->where('user_id', Auth::id())->exists())
                    ->form([
                        Textarea::make('cover_letter')
                            ->label('Cover Letter / Notes')
                            ->required()
                            ->placeholder('Describe your solution or write a brief statement of interest...'),
                        TextInput::make('submission_url')
                            ->label('Submission URL (e.g. GitHub Repository, Live Link)')
                            ->url()
                            ->required()
                            ->placeholder('https://github.com/...'),
                    ])
                    ->action(function (Challenge $record, array $data) {
                        ChallengeApplication::create([
                            'challenge_id' => $record->id,
                            'user_id' => Auth::id(),
                            'cover_letter' => $data['cover_letter'],
                            'submission_url' => $data['submission_url'],
                            'submitted_at' => now(),
                            'status' => 'pending',
                        ]);

                        Notification::make()
                            ->title('Application submitted successfully!')
                            ->success()
                            ->send();
                    }),

                \Filament\Actions\Action::make('already_applied')
                    ->label('Applied')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->disabled()
                    ->visible(fn (Challenge $record) => $record->applications()->where('user_id', Auth::id())->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => DiscoverOpportunitiesResource\Pages\ListOpportunities::route('/'),
            'view' => DiscoverOpportunitiesResource\Pages\ViewOpportunity::route('/{record}'),
        ];
    }
}
