<?php

namespace App\Filament\Student\Resources;

use App\Models\Project;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Portfolio Projects';

    protected static ?string $pluralModelLabel = 'Portfolio Projects';

    protected static ?string $modelLabel = 'Project';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
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
                        TextInput::make('project_url')
                            ->label('Project URL')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('repository_url')
                            ->label('Repository URL')
                            ->url()
                            ->maxLength(255),
                        FileUpload::make('image_path')
                            ->label('Screenshot/Image')
                            ->image()
                            ->disk('public')
                            ->directory('project-images'),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpan(2),
                        DatePicker::make('start_date')
                            ->label('Start Date'),
                        DatePicker::make('end_date')
                            ->label('End Date'),
                        Toggle::make('is_ongoing')
                            ->label('Is this project ongoing?')
                            ->default(false)
                            ->columnSpan(2),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Project Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('title')
                                    ->weight('semibold'),
                                TextEntry::make('project_url')
                                    ->label('Project URL')
                                    ->url(fn ($state) => $state)
                                    ->openUrlInNewTab(),
                                TextEntry::make('repository_url')
                                    ->label('Repository URL')
                                    ->url(fn ($state) => $state)
                                    ->openUrlInNewTab(),
                                TextEntry::make('start_date')
                                    ->date(),
                                TextEntry::make('end_date')
                                    ->date()
                                    ->visible(fn ($record) => ! $record?->is_ongoing),
                                TextEntry::make('is_ongoing')
                                    ->label('Status')
                                    ->formatStateUsing(fn ($state) => $state ? 'Ongoing' : 'Completed')
                                    ->badge()
                                    ->color(fn ($state) => $state ? 'warning' : 'success'),
                                ImageEntry::make('image_path')
                                    ->label('Screenshot/Image')
                                    ->disk('public')
                                    ->columnSpanFull(),
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
                    ->label('Image')
                    ->disk('public'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project_url')
                    ->label('URL')
                    ->limit(30),
                IconColumn::make('is_ongoing')
                    ->label('Ongoing')
                    ->boolean(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
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
            'index' => ProjectResource\Pages\ListProjects::route('/'),
            'create' => ProjectResource\Pages\CreateProject::route('/create'),
            'view' => ProjectResource\Pages\ViewProject::route('/{record}'),
            'edit' => ProjectResource\Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
