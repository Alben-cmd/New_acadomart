<?php

namespace App\Filament\Admin\Resources\Challenges\Pages;

use App\Filament\Admin\Resources\Challenges\ChallengeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewChallenge extends ViewRecord
{
    protected static string $resource = ChallengeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
