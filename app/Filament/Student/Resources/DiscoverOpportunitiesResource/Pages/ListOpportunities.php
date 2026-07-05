<?php

namespace App\Filament\Student\Resources\DiscoverOpportunitiesResource\Pages;

use App\Filament\Student\Resources\DiscoverOpportunitiesResource;
use Filament\Resources\Pages\ListRecords;

class ListOpportunities extends ListRecords
{
    protected static string $resource = DiscoverOpportunitiesResource::class;

    protected function getExtraPageWrapperAttributes(): array
    {
        return [
            'class' => 'max-w-full overflow-x-hidden',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
