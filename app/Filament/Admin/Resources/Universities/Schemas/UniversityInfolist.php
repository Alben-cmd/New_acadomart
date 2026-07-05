<?php

namespace App\Filament\Admin\Resources\Universities\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class UniversityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('University Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('University Name'),
                                TextEntry::make('short_name')
                                    ->label('Abbreviation'),
                                IconEntry::make('is_active')
                                    ->label('Status')
                                    ->boolean(),
                                TextEntry::make('country')
                                    ->label('Country'),
                                TextEntry::make('state')
                                    ->label('State'),
                                TextEntry::make('website_url')
                                    ->label('Website URL')
                                    ->url(fn ($record) => $record?->website_url, shouldOpenInNewTab: true)
                                    ->color('primary')
                                    ->default('-'),
                            ]),
                    ]),
            ]);
    }
}
