<?php

namespace App\Filament\Student\Resources\DiscoverOpportunitiesResource\Pages;

use App\Filament\Student\Resources\DiscoverOpportunitiesResource;
use Filament\Resources\Pages\ViewRecord;

class ViewOpportunity extends ViewRecord
{
    protected static string $resource = DiscoverOpportunitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('apply')
                ->label('Apply/Submit')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->hidden(fn () => $this->record->applications()->where('user_id', \Illuminate\Support\Facades\Auth::id())->exists())
                ->form([
                    \Filament\Forms\Components\Textarea::make('cover_letter')
                        ->label('Cover Letter / Notes')
                        ->required()
                        ->placeholder('Describe your solution or write a brief statement of interest...'),
                    \Filament\Forms\Components\TextInput::make('submission_url')
                        ->label('Submission URL (e.g. GitHub Repository, Live Link)')
                        ->url()
                        ->required()
                        ->placeholder('https://github.com/...'),
                ])
                ->action(function (array $data) {
                    \App\Models\ChallengeApplication::create([
                        'challenge_id' => $this->record->id,
                        'user_id' => \Illuminate\Support\Facades\Auth::id(),
                        'cover_letter' => $data['cover_letter'],
                        'submission_url' => $data['submission_url'],
                        'submitted_at' => now(),
                        'status' => 'pending',
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->title('Application submitted successfully!')
                        ->success()
                        ->send();
                }),

            \Filament\Actions\Action::make('already_applied')
                ->label('Applied')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->disabled()
                ->visible(fn () => $this->record->applications()->where('user_id', \Illuminate\Support\Facades\Auth::id())->exists()),
        ];
    }
}
