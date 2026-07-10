<x-mail::message>
# Opportunity Application Approved

Hello {{ $application->user->name }},

We are pleased to inform you that your application for the opportunity **{{ $application->challenge->title }}** has been approved by **{{ $application->challenge->company->name }}**!

<x-mail::button :url="\App\Filament\Student\Resources\DiscoverOpportunitiesResource::getUrl('view', ['record' => $application->challenge_id], panel: 'student')">
View Opportunity Details
</x-mail::button>

Congratulations and best of luck!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
