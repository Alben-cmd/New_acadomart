<x-mail::message>
# New Opportunity Application

Hello {{ $application->challenge->company->user->name }},

**{{ $application->user->name }}** has applied for the opportunity: **{{ $application->challenge->title }}**.

**Cover Letter / Submission Notes:**
{{ $application->cover_letter }}

**Submission URL:**
[{{ $application->submission_url }}]({{ $application->submission_url }})

<x-mail::button :url="\App\Filament\Industry\Resources\ManageChallengesResource::getUrl('view', ['record' => $application->challenge_id], panel: 'industry')">
View Application
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
