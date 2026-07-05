<?php

namespace App\Filament\Student\Resources;

use App\Models\Badge;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class BadgeResource extends Resource
{
    protected static ?string $model = Badge::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Earned Badges';

    protected static ?string $pluralModelLabel = 'Earned Badges';

    protected static ?string $modelLabel = 'Badge';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('skill_id')
                    ->label('Associated Skill')
                    ->options(\App\Models\Skill::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                FileUpload::make('image_path')
                    ->label('Badge Icon')
                    ->image()
                    ->disk('public')
                    ->directory('badges'),
                TextInput::make('verification_source')
                    ->label('Verification URL')
                    ->url()
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Badge Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->weight('semibold'),
                                TextEntry::make('skill.name')
                                    ->label('Associated Skill'),
                                ImageEntry::make('image_path')
                                    ->label('Badge Icon')
                                    ->disk('public')
                                    ->circular(),
                                TextEntry::make('verification_source')
                                    ->label('Verification URL')
                                    ->url(fn ($record) => $record->verification_source)
                                    ->openUrlInNewTab(),
                                TextEntry::make('description')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Badge Icon')
                    ->disk('public'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('skill.name')
                    ->label('Associated Skill')
                    ->searchable(),
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

    public static function getPages(): array
    {
        return [
            'index' => BadgeResource\Pages\ListBadges::route('/'),
            'create' => BadgeResource\Pages\CreateBadge::route('/create'),
            'view' => BadgeResource\Pages\ViewBadge::route('/{record}'),
            'edit' => BadgeResource\Pages\EditBadge::route('/{record}/edit'),
        ];
    }
}
