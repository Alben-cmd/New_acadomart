<?php

namespace App\Filament\Student\Resources\BadgeResource\Pages;

use App\Filament\Student\Resources\BadgeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBadge extends CreateRecord
{
    protected static string $resource = BadgeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
