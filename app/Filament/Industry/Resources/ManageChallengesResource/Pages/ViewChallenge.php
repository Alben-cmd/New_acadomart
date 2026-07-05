<?php

namespace App\Filament\Industry\Resources\ManageChallengesResource\Pages;

use App\Filament\Industry\Resources\ManageChallengesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChallenge extends ViewRecord
{
    protected static string $resource = ManageChallengesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
