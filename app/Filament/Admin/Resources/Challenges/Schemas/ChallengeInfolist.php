<?php

namespace App\Filament\Admin\Resources\Challenges\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class ChallengeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Challenge Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Title'),
                                TextEntry::make('company.name')
                                    ->label('Company')
                                    ->default('-'),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge(),
                                TextEntry::make('type')
                                    ->label('Opportunity Type')
                                    ->badge()
                                    ->color('gray'),
                                TextEntry::make('location')
                                    ->label('Location')
                                    ->default('-'),
                                TextEntry::make('duration')
                                    ->label('Duration')
                                    ->default('-'),
                                TextEntry::make('deadline')
                                    ->label('Deadline')
                                    ->date(),
                                TextEntry::make('reward')
                                    ->label('Reward')
                                    ->default('-'),
                                TextEntry::make('skills.name')
                                    ->label('Required Skills')
                                    ->badge()
                                    ->columnSpanFull(),
                            ]),
                        TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                        TextEntry::make('requirements')
                            ->label('Requirements')
                            ->default('No specific requirements specified.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
