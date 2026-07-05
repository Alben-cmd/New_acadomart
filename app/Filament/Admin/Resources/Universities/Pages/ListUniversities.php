<?php

namespace App\Filament\Admin\Resources\Universities\Pages;

use App\Filament\Admin\Resources\Universities\UniversityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUniversities extends ListRecords
{
    protected static string $resource = UniversityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
