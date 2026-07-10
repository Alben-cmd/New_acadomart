<?php

namespace App\Filament\Industry\Resources\ManageChallengesResource\RelationManagers;

use App\Models\ChallengeApplication;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ApplicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'applications';

    protected static ?string $title = 'Applicants & Submissions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'reviewing' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                    }),
                TextColumn::make('submission_url')
                    ->label('Submission Link')
                    ->limit(30),
                TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\ViewAction::make()
                    ->label('Review')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->url(fn (ChallengeApplication $record) => \App\Filament\Industry\Resources\ChallengeApplicationResource::getUrl('view', ['record' => $record], panel: 'industry')),
            ])
            ->bulkActions([
                //
            ]);
    }
}
