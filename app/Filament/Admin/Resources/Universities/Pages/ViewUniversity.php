<?php

namespace App\Filament\Admin\Resources\Universities\Pages;

use App\Filament\Admin\Resources\Universities\UniversityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUniversity extends ViewRecord
{
    protected static string $resource = UniversityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
