<?php

namespace App\Filament\Industry\Resources\ChallengeApplicationResource\Pages;

use App\Filament\Industry\Resources\ChallengeApplicationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ViewChallengeApplication extends ViewRecord
{
    protected static string $resource = ChallengeApplicationResource::class;

    protected string $view = 'filament.industry.pages.view-challenge-application';

    public ?array $statusData = [];

    public function mount($record): void
    {
        parent::mount($record);

        $this->statusForm->fill([
            'status' => $this->getRecord()->status,
        ]);
    }

    protected function getForms(): array
    {
        return [
            'form',
            'statusForm',
        ];
    }

    public function statusForm(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('status')
                    ->label('Application Status')
                    ->options([
                        'pending' => 'Pending',
                        'reviewing' => 'Reviewing',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
            ])
            ->statePath('statusData');
    }

    public function getStatusFormActions(): array
    {
        return [
            Action::make('saveStatus')
                ->label('Update Status')
                ->color('primary')
                ->submit('saveStatus'),
        ];
    }

    public function saveStatus(): void
    {
        $data = $this->statusForm->getState();

        $this->getRecord()->update([
            'status' => $data['status'],
        ]);

        Notification::make()
            ->title('Application status updated!')
            ->success()
            ->send();
    }
}
