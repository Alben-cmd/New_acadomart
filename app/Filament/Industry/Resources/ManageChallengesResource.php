<?php

namespace App\Filament\Industry\Resources;

use App\Models\Challenge;
use App\Filament\Industry\Resources\ManageChallengesResource\RelationManagers\ApplicationsRelationManager;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ManageChallengesResource extends Resource
{
    protected static ?string $model = Challenge::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Manage Opportunities';

    protected static ?string $pluralModelLabel = 'Opportunities & Challenges';
 
    protected static ?string $modelLabel = 'Opportunity';

    public static function getEloquentQuery(): Builder
    {
        $companyId = Auth::user()->company?->id ?? 0;
        return parent::getEloquentQuery()->where('company_id', $companyId);
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->label('Opportunity Type')
                            ->options([
                                'Full-time' => 'Full-time',
                                'Part-time' => 'Part-time',
                                'Internship' => 'Internship',
                                'Contract' => 'Contract',
                            ])
                            ->required(),
                        TextInput::make('location')
                            ->placeholder('e.g., Lagos, Nigeria or Remote')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('duration')
                            ->placeholder('e.g., 3 months')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('deadline')
                            ->required(),
                        TextInput::make('reward')
                            ->label('Reward/Incentive')
                            ->required()
                            ->placeholder('e.g., $500, Internship Offer'),
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('active')
                            ->required(),
                        Select::make('skills')
                            ->label('Required Skills / Tags')
                            ->multiple()
                            ->relationship('skills', 'name')
                            ->preload()
                            ->searchable(),
                        Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpan(2),
                        Textarea::make('requirements')
                            ->rows(3)
                            ->columnSpan(2),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Challenge Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('title')
                                    ->weight('bold')
                                    ->columnSpan(2),
                                TextEntry::make('type')
                                    ->label('Opportunity Type')
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
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'active' => 'success',
                                        'completed' => 'info',
                                        'cancelled' => 'danger',
                                    }),
                                TextEntry::make('skills.name')
                                    ->label('Required Skills')
                                    ->badge()
                                    ->columnSpan(2),
                                TextEntry::make('description')
                                    ->columnSpan(2),
                                TextEntry::make('requirements')
                                    ->default('No specific requirements listed.')
                                    ->columnSpan(2),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reward')
                    ->sortable(),
                TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                    }),
                TextColumn::make('applications_count')
                    ->label('Applicants')
                    ->counts('applications'),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ApplicationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageChallengesResource\Pages\ListChallenges::route('/'),
            'create' => ManageChallengesResource\Pages\CreateChallenge::route('/create'),
            'view' => ManageChallengesResource\Pages\ViewChallenge::route('/{record}'),
            'edit' => ManageChallengesResource\Pages\EditChallenge::route('/{record}/edit'),
        ];
    }
}
