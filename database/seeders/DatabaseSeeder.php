<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\University;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed States and LGAs
        $this->call(StateLgaSeeder::class);

        // 2. Seed Universities
        $universities = [
            [
                'name' => 'University of Ibadan',
                'short_name' => 'UI',
                'country' => 'Nigeria',
                'state' => 'Oyo',
                'website_url' => 'https://ui.edu.ng',
                'is_active' => true,
            ],
            [
                'name' => 'University of Lagos',
                'short_name' => 'UNILAG',
                'country' => 'Nigeria',
                'state' => 'Lagos',
                'website_url' => 'https://unilag.edu.ng',
                'is_active' => true,
            ],
            [
                'name' => 'Obafemi Awolowo University',
                'short_name' => 'OAU',
                'country' => 'Nigeria',
                'state' => 'Osun',
                'website_url' => 'https://oauife.edu.ng',
                'is_active' => true,
            ],
            [
                'name' => 'Covenant University',
                'short_name' => 'CU',
                'country' => 'Nigeria',
                'state' => 'Ogun',
                'website_url' => 'https://covenantuniversity.edu.ng',
                'is_active' => true,
            ],
        ];

        foreach ($universities as $uni) {
            University::create($uni);
        }

        // 3. Seed Skills
        $skills = [
            'PHP',
            'Laravel',
            'React',
            'Vue.js',
            'Tailwind CSS',
            'Python',
            'MySQL',
            'Docker',
            'Git',
        ];

        foreach ($skills as $skillName) {
            Skill::create([
                'name' => $skillName,
                'slug' => Str::slug($skillName),
            ]);
        }

        // 4. Seed Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => \App\Enums\UserRole::Admin,
        ]);

        // 5. Seed Student User
        $student = User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
            'role' => \App\Enums\UserRole::Student,
        ]);

        // 6. Seed Industry User
        User::factory()->create([
            'name' => 'Industry User',
            'email' => 'industry@example.com',
            'password' => bcrypt('password'),
            'role' => \App\Enums\UserRole::Industry,
        ]);

        // 7. Seed Researcher User
        User::factory()->create([
            'name' => 'Researcher User',
            'email' => 'researcher@example.com',
            'password' => bcrypt('password'),
            'role' => \App\Enums\UserRole::Researcher,
        ]);

        // Update the student user's profile with some realistic sample data
        $ui = University::where('short_name', 'UI')->first();
        $lagosState = \App\Models\State::where('name', 'Lagos')->first();
        $ikejaLga = \App\Models\Lga::where('name', 'Ikeja')->first();

        if ($student->profile) {
            $student->profile->update([
                'university_id' => $ui?->id,
                'matric_no' => 'UI/2026/0405',
                'department' => 'Computer Science',
                'graduation_year' => 2026,
                'cgpa' => 4.85,
                'state_id' => $lagosState?->id,
                'lga_id' => $ikejaLga?->id,
                'bio' => 'Computer Science student passionate about Laravel and web development.',
            ]);
        }

        // Give the student some initial skills
        $phpSkill = Skill::where('name', 'PHP')->first();
        $laravelSkill = Skill::where('name', 'Laravel')->first();

        if ($phpSkill) {
            $student->skills()->attach($phpSkill->id, ['level' => 'Advanced']);
        }
        if ($laravelSkill) {
            $student->skills()->attach($laravelSkill->id, ['level' => 'Intermediate']);
        }

        // Seed a project for the student
        $student->projects()->create([
            'title' => 'Acadomart E-Commerce Platform',
            'description' => 'A multi-vendor e-commerce platform built using Filament and Laravel.',
            'project_url' => 'https://acadomart.example.com',
            'repository_url' => 'https://github.com/example/acadomart',
            'start_date' => '2026-01-01',
            'end_date' => '2026-05-30',
            'is_ongoing' => false,
        ]);

        // Seed a badge for the student
        $student->badges()->create([
            'name' => 'Laravel Certified Developer',
            'description' => 'Successfully passed the Laravel certification exam.',
            'verification_source' => 'https://laravel.com/certification/verify',
            'skill_id' => $laravelSkill?->id,
        ]);

        // Create a challenge for the industry user
        $industryUser = User::where('email', 'industry@example.com')->first();
        if ($industryUser && $industryUser->company) {
            $company = $industryUser->company;
            $company->update([
                'name' => 'TechCorp Nigeria',
            ]);

            // Create a frontend developer intern opportunity
            $challenge = $company->challenges()->create([
                'title' => 'Frontend Developer Intern',
                'description' => 'Join our frontend team to build modern web applications using React and TypeScript.',
                'requirements' => 'React, JavaScript, CSS, Git',
                'deadline' => now()->addMonths(2),
                'reward' => 'Internship Offer & Stipend',
                'status' => 'active',
                'duration' => '3 months',
                'location' => 'Lagos, Nigeria',
                'type' => 'Full-time',
                'created_at' => now()->subDays(2),
            ]);

            // Attach skills to the challenge
            $reactSkill = Skill::where('name', 'React')->first();
            $gitSkill = Skill::where('name', 'Git')->first();
            if ($reactSkill) {
                $challenge->skills()->attach($reactSkill->id);
            }
            if ($gitSkill) {
                $challenge->skills()->attach($gitSkill->id);
            }

            // Create a backend laravel developer opportunity
            $challenge2 = $company->challenges()->create([
                'title' => 'Backend Laravel Developer',
                'description' => 'We are seeking a Backend Developer Intern to help design and maintain our database schemas, build REST APIs, and implement custom logic for our business-critical platforms.',
                'requirements' => 'PHP, Laravel, MySQL, Git',
                'deadline' => now()->addMonths(1),
                'reward' => 'Potential Full-time Hire',
                'status' => 'active',
                'duration' => '6 months',
                'location' => 'Abuja, Nigeria (Hybrid)',
                'type' => 'Internship',
                'created_at' => now()->subDays(5),
            ]);

            $phpSkill = Skill::where('name', 'PHP')->first();
            $laravelSkill2 = Skill::where('name', 'Laravel')->first();
            $mysqlSkill = Skill::where('name', 'MySQL')->first();
            if ($phpSkill) {
                $challenge2->skills()->attach($phpSkill->id);
            }
            if ($laravelSkill2) {
                $challenge2->skills()->attach($laravelSkill2->id);
            }
            if ($mysqlSkill) {
                $challenge2->skills()->attach($mysqlSkill->id);
            }
            if ($gitSkill) {
                $challenge2->skills()->attach($gitSkill->id);
            }
        }
    }
}
