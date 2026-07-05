<?php

namespace App\Filament\Admin\Resources\Challenges\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use App\Models\Company;

class ChallengeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->label('Company')
                    ->options(Company::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
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
                    ->required()
                    ->placeholder('e.g., $500, Internship Opportunity'),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('active'),
                Select::make('skills')
                    ->label('Required Skills / Tags')
                    ->multiple()
                    ->relationship('skills', 'name')
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
                Textarea::make('requirements')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
