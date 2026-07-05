<?php

namespace App\Filament\Industry\Resources\ManageChallengesResource\Pages;

use App\Filament\Industry\Resources\ManageChallengesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChallenges extends ListRecords
{
    protected static string $resource = ManageChallengesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
