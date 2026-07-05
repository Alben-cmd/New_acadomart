<?php

namespace App\Filament\Industry\Resources;

use App\Models\User;
use App\Models\University;
use App\Models\State;
use App\Models\Skill;
use App\Enums\UserRole;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Builder;

class TalentSearchResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Search Talent';

    protected static ?string $pluralModelLabel = 'Student Talent';

    protected static ?string $modelLabel = 'Student';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', UserRole::Student);
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Student Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')->disabled(),
                                TextInput::make('email')->disabled(),
                                TextInput::make('phone')->label('Phone Number')->disabled(),
                                TextInput::make('profile.matric_no')->label('Matric Number')->disabled(),
                                TextInput::make('profile.university.name')->label('University')->disabled(),
                                TextInput::make('profile.department')->label('Department')->disabled(),
                                TextInput::make('profile.cgpa')->label('CGPA')->disabled(),
                                TextInput::make('profile.graduation_year')->label('Graduation Year')->disabled(),
                                TextInput::make('profile.state.name')->label('State of Origin')->disabled(),
                                TextInput::make('profile.lga.name')->label('LGA')->disabled(),
                                Textarea::make('profile.bio')->label('Bio')->disabled()->columnSpan(2),
                            ]),
                    ]),

                Section::make('Skills & Portfolio')
                    ->schema([
                        Placeholder::make('portfolio_details')
                            ->label('')
                            ->content(fn (?User $record) => $record ? view('filament.industry.components.student-portfolio', ['student' => $record]) : ''),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Student Information')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                ImageEntry::make('profile.profile_picture')
                                    ->label('')
                                    ->circular()
                                    ->height(100)
                                    ->disk('public')
                                    ->columnSpan(1),
                                Grid::make(3)
                                    ->columnSpan(3)
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Full Name')
                                            ->weight('bold'),
                                        TextEntry::make('email')
                                            ->label('Email Address')
                                            ->copyable(),
                                        TextEntry::make('phone')
                                            ->label('Phone Number')
                                            ->default('-'),
                                        TextEntry::make('profile.matric_no')
                                            ->label('Matric Number')
                                            ->default('-'),
                                        TextEntry::make('profile.university.name')
                                            ->label('University')
                                            ->default('-'),
                                        TextEntry::make('profile.department')
                                            ->label('Department')
                                            ->default('-'),
                                        TextEntry::make('profile.graduation_year')
                                            ->label('Graduation Year')
                                            ->default('-'),
                                        TextEntry::make('profile.cgpa')
                                            ->label('CGPA')
                                            ->default('-'),
                                        TextEntry::make('profile.state.name')
                                            ->label('State of Origin')
                                            ->default('-'),
                                        TextEntry::make('profile.lga.name')
                                            ->label('LGA')
                                            ->default('-'),
                                    ]),
                                TextEntry::make('profile.bio')
                                    ->label('Biography')
                                    ->default('No biography provided.')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Skills & Portfolio')
                    ->schema([
                        Placeholder::make('portfolio_details')
                            ->label('')
                            ->content(fn (?User $record) => $record ? view('filament.industry.components.student-portfolio', ['student' => $record]) : ''),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('profile.university.name')
                                    ->label('University')
                                    ->searchable()
                                    ->sortable(),
                                TextColumn::make('profile.department')
                                    ->label('Department')
                                    ->searchable()
                                    ->sortable(),
                                TextColumn::make('profile.cgpa')
                                    ->label('CGPA')
                                    ->sortable(),
                TextColumn::make('profile.state.name')
                    ->label('State')
                    ->sortable(),
                TextColumn::make('skills.name')
                    ->label('Skills')
                    ->badge()
                    ->limitList(3),
            ])
            ->filters([
                SelectFilter::make('university')
                    ->relationship('profile.university', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('state')
                    ->relationship('profile.state', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make('high_cgpa')
                    ->label('High CGPA (>= 4.0)')
                    ->query(fn (Builder $query) => $query->whereHas('profile', fn ($q) => $q->where('cgpa', '>=', 4.0))),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => TalentSearchResource\Pages\ListTalents::route('/'),
            'view' => TalentSearchResource\Pages\ViewTalent::route('/{record}'),
        ];
    }
}
