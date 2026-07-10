<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'user_id',
        'status',
        'cover_letter',
        'submission_url',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (ChallengeApplication $application) {
            $challenge = $application->challenge;
            $student = $application->user;
            $industryUser = $challenge->company?->user;

            if ($industryUser) {
                // 1. Send database notification to the industry user (synchronously)
                $notification = \Filament\Notifications\Notification::make()
                    ->title('New Opportunity Application')
                    ->body("{$student->name} has applied for \"{$challenge->title}\".")
                    ->info()
                    ->actions([
                        \Filament\Actions\Action::make('view')
                            ->button()
                            ->url(\App\Filament\Industry\Resources\ManageChallengesResource::getUrl('view', ['record' => $challenge->id], panel: 'industry')),
                    ]);

                $industryUser->notifyNow($notification->toDatabase());

                // 2. Send email to the industry user
                \Illuminate\Support\Facades\Mail::to($industryUser->email)
                    ->send(new \App\Mail\NewChallengeApplicationMail($application));
            }
        });

        static::updated(function (ChallengeApplication $application) {
            if ($application->wasChanged('status') && $application->status === 'accepted') {
                $challenge = $application->challenge;
                $student = $application->user;

                if ($student) {
                    // 1. Send database notification to the student (synchronously)
                    $notification = \Filament\Notifications\Notification::make()
                        ->title('Opportunity Application Approved')
                        ->body("Your application for \"{$challenge->title}\" has been approved!")
                        ->success()
                        ->actions([
                            \Filament\Actions\Action::make('view')
                                ->button()
                                ->url(\App\Filament\Student\Resources\DiscoverOpportunitiesResource::getUrl('view', ['record' => $challenge->id], panel: 'student')),
                        ]);

                    $student->notifyNow($notification->toDatabase());

                    // 2. Send email to the student
                    \Illuminate\Support\Facades\Mail::to($student->email)
                        ->send(new \App\Mail\ChallengeApplicationApprovedMail($application));
                }
            }
        });
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
