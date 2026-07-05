<?php

namespace App\Filament\Admin\Resources\Universities\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class UniversityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('short_name')
                    ->label('Abbreviation')
                    ->required()
                    ->maxLength(50),
                TextInput::make('country')
                    ->required()
                    ->default('Nigeria')
                    ->maxLength(100),
                TextInput::make('state')
                    ->required()
                    ->maxLength(100),
                TextInput::make('website_url')
                    ->url()
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
