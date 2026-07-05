<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Name'),
                                TextEntry::make('email')
                                    ->label('Email'),
                                TextEntry::make('role')
                                    ->label('Role')
                                    ->badge(),
                                TextEntry::make('created_at')
                                    ->label('Registered At')
                                    ->dateTime(),
                            ]),
                    ]),
                Section::make('Student Profile')
                    ->visible(fn ($record) => $record?->role?->value === 'student')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('profile.university.name')
                                    ->label('University')
                                    ->default('-'),
                                TextEntry::make('profile.department')
                                    ->label('Department')
                                    ->default('-'),
                                TextEntry::make('profile.matric_no')
                                    ->label('Matric Number')
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
                            ]),
                        TextEntry::make('profile.bio')
                            ->label('Biography')
                            ->default('No biography provided.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Company Profile')
                    ->visible(fn ($record) => $record?->role?->value === 'industry')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('company.name')
                                    ->label('Company Name')
                                    ->default('-'),
                                TextEntry::make('company.website_url')
                                    ->label('Website')
                                    ->default('-'),
                            ]),
                    ]),
            ]);
    }
}
