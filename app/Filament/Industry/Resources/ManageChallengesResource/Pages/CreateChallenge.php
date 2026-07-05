<?php

namespace App\Filament\Industry\Resources\ManageChallengesResource\Pages;

use App\Filament\Industry\Resources\ManageChallengesResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateChallenge extends CreateRecord
{
    protected static string $resource = ManageChallengesResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $company = $user->company;

        if (! $company) {
            $company = $user->company()->create([
                'name' => $user->name . ' Company',
            ]);
        }

        $data['company_id'] = $company->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
