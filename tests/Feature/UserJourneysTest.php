<?php

use App\Models\User;
use App\Models\University;
use App\Models\State;
use App\Models\Lga;
use App\Models\Skill;
use App\Models\Project;
use App\Models\Challenge;
use App\Models\ChallengeApplication;
use App\Enums\UserRole;
use Livewire\Livewire;
use Filament\Facades\Filament;
use App\Filament\Student\Pages\EditProfile;
use App\Filament\Student\Resources\ProjectResource\Pages\ListProjects;
use App\Filament\Student\Resources\DiscoverOpportunitiesResource\Pages\ListOpportunities;
use App\Filament\Industry\Pages\EditCompanyProfile;
use App\Filament\Industry\Resources\ManageChallengesResource\Pages\CreateChallenge;
use App\Filament\Industry\Resources\ManageChallengesResource\Pages\ListChallenges;
use App\Filament\Industry\Resources\ManageChallengesResource\Pages\EditChallenge;
use App\Filament\Industry\Resources\ManageChallengesResource\RelationManagers\ApplicationsRelationManager;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewChallengeApplicationMail;

beforeEach(function () {
    Mail::fake();
    
    // Seed essential lookups
    $this->state = State::create(['name' => 'LagosState']);
    $this->lga = Lga::create(['state_id' => $this->state->id, 'name' => 'Ikeja']);
    $this->university = University::create([
        'name' => 'University of Lagos',
        'short_name' => 'UNILAG',
        'country' => 'Nigeria',
        'state' => 'Lagos',
        'is_active' => true,
    ]);

    $this->skillPhp = Skill::create(['name' => 'PHP', 'slug' => 'php']);
    $this->skillLaravel = Skill::create(['name' => 'Laravel', 'slug' => 'laravel']);
});

test('student can build profile and showcase skills', function () {
    Filament::setCurrentPanel('student');
    $student = User::factory()->create(['role' => UserRole::Student]);

    Livewire::actingAs($student)
        ->test(EditProfile::class)
        ->set('isEditing', true)
        ->fillForm([
            'bio' => 'Passionate student coder.',
            'university_id' => $this->university->id,
            'matric_no' => 'MAT123',
            'department' => 'Computer Science',
            'graduation_year' => 2027,
            'cgpa' => 4.50,
            'state_id' => $this->state->id,
            'lga_id' => $this->lga->id,
            'skills' => [
                ['skill_id' => $this->skillPhp->id, 'level' => 'Advanced'],
                ['skill_id' => $this->skillLaravel->id, 'level' => 'Intermediate'],
            ]
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $student->refresh();

    expect($student->profile->bio)->toBe('Passionate student coder.');
    expect($student->profile->university_id)->toBe($this->university->id);
    expect($student->profile->cgpa)->toEqual(4.50);
    expect($student->skills)->toHaveCount(2);
    expect($student->skills->pluck('name'))->toContain('PHP', 'Laravel');
});

test('student can upload portfolio projects', function () {
    Filament::setCurrentPanel('student');
    $student = User::factory()->create(['role' => UserRole::Student]);

    Livewire::actingAs($student)
        ->test(ListProjects::class)
        ->callAction('create', [
            'title' => 'My Awesome App',
            'description' => 'A great app built in Laravel',
            'project_url' => 'https://awesomeapp.com',
            'repository_url' => 'https://github.com/test/awesomeapp',
            'is_ongoing' => true,
        ])
        ->assertHasNoActionErrors();

    $student->refresh();

    expect($student->projects)->toHaveCount(1);
    expect($student->projects->first()->title)->toBe('My Awesome App');
    expect($student->projects->first()->is_ongoing)->toBeTrue();
});

test('student can discover opportunities and apply', function () {
    Filament::setCurrentPanel('student');
    $student = User::factory()->create(['role' => UserRole::Student]);
    $industryUser = User::factory()->create(['role' => UserRole::Industry]);
    
    $company = $industryUser->company;
    $challenge = Challenge::create([
        'company_id' => $company->id,
        'title' => 'Hackathon 2026',
        'description' => 'Build a cool project',
        'reward' => '$1000',
        'status' => 'active',
        'deadline' => now()->addDays(10),
    ]);

    Livewire::actingAs($student)
        ->test(ListOpportunities::class)
        ->callTableAction('apply', $challenge, data: [
            'cover_letter' => 'I would love to build this.',
            'submission_url' => 'https://github.com/mysolution',
        ])
        ->assertHasNoTableActionErrors();

    expect(ChallengeApplication::where('user_id', $student->id)->count())->toBe(1);
    $application = ChallengeApplication::where('user_id', $student->id)->first();
    expect($application->challenge_id)->toBe($challenge->id);
    expect($application->cover_letter)->toBe('I would love to build this.');
    expect($application->status)->toBe('pending');
});

test('industry can create/edit company profile', function () {
    Filament::setCurrentPanel('industry');
    $industryUser = User::factory()->create(['role' => UserRole::Industry]);

    Livewire::actingAs($industryUser)
        ->test(EditCompanyProfile::class)
        ->fillForm([
            'name' => 'Acme Corp',
            'industry_sector' => 'Technology',
            'website_url' => 'https://acme.org',
            'description' => 'We do software development.',
            'state_id' => $this->state->id,
            'lga_id' => $this->lga->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $industryUser->refresh();

    expect($industryUser->company->name)->toBe('Acme Corp');
    expect($industryUser->company->industry_sector)->toBe('Technology');
    expect($industryUser->company->state_id)->toBe($this->state->id);
});

test('industry can post challenges and review applicants', function () {
    Filament::setCurrentPanel('industry');
    $industryUser = User::factory()->create(['role' => UserRole::Industry]);
    $student = User::factory()->create(['role' => UserRole::Student]);

    // Create a challenge via the Create page
    Livewire::actingAs($industryUser)
        ->test(CreateChallenge::class)
        ->fillForm([
            'title' => 'Backend Challenge',
            'description' => 'Build a backend API.',
            'reward' => '$500',
            'deadline' => now()->addDays(5)->toDateString(),
            'status' => 'active',
            'type' => 'Internship',
            'location' => 'Remote',
            'duration' => '3 months',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $challenge = Challenge::where('title', 'Backend Challenge')->first();
    expect($challenge)->not->toBeNull();
    expect($challenge->company_id)->toBe($industryUser->company->id);

    // Apply to challenge
    $application = ChallengeApplication::create([
        'challenge_id' => $challenge->id,
        'user_id' => $student->id,
        'cover_letter' => 'Applying to this challenge.',
        'submission_url' => 'https://github.com/student/solution',
        'submitted_at' => now(),
        'status' => 'pending',
    ]);

    // Review application in Relation Manager
    Livewire::actingAs($industryUser)
        ->test(ApplicationsRelationManager::class, [
            'ownerRecord' => $challenge,
            'pageClass' => EditChallenge::class,
        ])
        ->callTableAction('review', $application, data: [
            'status' => 'accepted',
        ])
        ->assertHasNoTableActionErrors();

    $application->refresh();
    expect($application->status)->toBe('accepted');
});

test('student application sends email and database notification to industry user', function () {
    $student = User::factory()->create(['role' => UserRole::Student]);
    $industryUser = User::factory()->create(['role' => UserRole::Industry]);
    
    $company = $industryUser->company;
    $challenge = Challenge::create([
        'company_id' => $company->id,
        'title' => 'Hackathon 2026',
        'description' => 'Build a cool project',
        'reward' => '$1000',
        'status' => 'active',
        'deadline' => now()->addDays(10),
        'type' => 'Internship',
        'location' => 'Remote',
        'duration' => '3 months',
    ]);

    // Apply to challenge
    $application = ChallengeApplication::create([
        'challenge_id' => $challenge->id,
        'user_id' => $student->id,
        'cover_letter' => 'I would love to build this.',
        'submission_url' => 'https://github.com/mysolution',
        'submitted_at' => now(),
        'status' => 'pending',
    ]);

    // Assert email was sent to industry user
    Mail::assertSent(NewChallengeApplicationMail::class, function ($mail) use ($industryUser, $application) {
        return $mail->hasTo($industryUser->email) && $mail->application->id === $application->id;
    });

    // Assert database notification exists for industry user
    $notification = $industryUser->notifications()->first();
    expect($notification)->not->toBeNull();
    expect($notification->data['title'])->toContain('New Opportunity Application');
    expect($notification->data['body'])->toContain($student->name);
});
