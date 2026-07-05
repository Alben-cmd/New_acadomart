<?php

use App\Models\User;
use App\Models\Profile;
use App\Models\University;
use App\Models\State;
use App\Models\Lga;
use App\Models\Skill;
use App\Models\Badge;
use App\Models\Project;
use App\Enums\UserRole;

test('creating a user automatically creates a profile', function () {
    $user = User::factory()->create([
        'email' => 'newuser@example.com',
        'role' => UserRole::Student,
    ]);

    expect($user->profile)->not->toBeNull();
    expect($user->profile->user_id)->toBe($user->id);
});

test('profile belongs to user, university, state, and lga', function () {
    $state = State::create(['name' => 'KwaraState']);
    $lga = Lga::create(['state_id' => $state->id, 'name' => 'Ilorin West']);
    
    $university = University::create([
        'name' => 'University of Ilorin',
        'short_name' => 'UNILORIN',
        'country' => 'Nigeria',
        'state' => 'Kwara',
        'is_active' => true,
    ]);

    $user = User::factory()->create([
        'email' => 'ilorinstudent@example.com',
        'role' => UserRole::Student,
    ]);

    $profile = $user->profile;
    $profile->update([
        'university_id' => $university->id,
        'state_id' => $state->id,
        'lga_id' => $lga->id,
        'matric_no' => '12/34AB56',
        'graduation_year' => 2027,
        'gpa' => 4.50,
    ]);

    expect($profile->user->id)->toBe($user->id);
    expect($profile->university->id)->toBe($university->id);
    expect($profile->state->id)->toBe($state->id);
    expect($profile->lga->id)->toBe($lga->id);
});

test('user can have multiple skills with proficiency level', function () {
    $user = User::factory()->create([
        'email' => 'skilleduser@example.com',
        'role' => UserRole::Student,
    ]);

    $skill1 = Skill::create(['name' => 'Tailwind CSS', 'slug' => 'tailwind-css']);
    $skill2 = Skill::create(['name' => 'Vue.js', 'slug' => 'vuejs']);

    $user->skills()->attach($skill1->id, ['level' => 'Expert']);
    $user->skills()->attach($skill2->id, ['level' => 'Intermediate']);

    expect($user->skills)->toHaveCount(2);
    expect($user->skills->first()->pivot->level)->toBe('Expert');
    expect($user->skills->last()->pivot->level)->toBe('Intermediate');
});

test('user can have multiple badges', function () {
    $user = User::factory()->create([
        'email' => 'badgeduser@example.com',
        'role' => UserRole::Student,
    ]);

    $skill = Skill::create(['name' => 'React', 'slug' => 'react']);

    $badge = Badge::create([
        'user_id' => $user->id,
        'name' => 'React Guru',
        'description' => 'Completed React advanced challenge',
        'verification_source' => 'https://react.dev/verify',
        'skill_id' => $skill->id,
    ]);

    expect($user->badges)->toHaveCount(1);
    expect($user->badges->first()->name)->toBe('React Guru');
    expect($user->badges->first()->skill->name)->toBe('React');
});

test('user can have multiple projects', function () {
    $user = User::factory()->create([
        'email' => 'projectuser@example.com',
        'role' => UserRole::Student,
    ]);

    $project = Project::create([
        'user_id' => $user->id,
        'title' => 'Acadomart API',
        'description' => 'Restful API backend for acadomart',
        'project_url' => 'https://api.acadomart.com',
        'is_ongoing' => true,
    ]);

    expect($user->projects)->toHaveCount(1);
    expect($user->projects->first()->title)->toBe('Acadomart API');
    expect($user->projects->first()->is_ongoing)->toBeTrue();
});
