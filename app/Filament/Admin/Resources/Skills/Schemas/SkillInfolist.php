<?php

namespace App\Filament\Admin\Resources\Skills\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class SkillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Skill Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Skill Name'),
                                TextEntry::make('slug')
                                    ->label('Slug'),
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime()
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
