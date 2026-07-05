<?php

namespace App\Filament\Industry\Resources\ManageChallengesResource\Pages;

use App\Filament\Industry\Resources\ManageChallengesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChallenge extends EditRecord
{
    protected static string $resource = ManageChallengesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
