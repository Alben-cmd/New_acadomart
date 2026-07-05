<?php

namespace App\Filament\Student\Pages;

use App\Models\University;
use App\Models\State;
use App\Models\Lga;
use App\Models\Skill;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'My Profile';

    protected string $view = 'filament.student.pages.edit-profile';

    public ?array $data = [];

    public bool $isEditing = false;

    public function getTitle(): string
    {
        return $this->isEditing ? 'Edit My Profile' : 'My Profile';
    }

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (! $profile) {
            $profile = $user->profile()->create();
        }

        $skillsData = $user->skills->map(function ($skill) {
            return [
                'skill_id' => $skill->id,
                'level' => $skill->pivot->level,
            ];
        })->toArray();

        $this->form->fill([
            'profile_picture' => $profile?->profile_picture ? (is_array($profile->profile_picture) ? $profile->profile_picture : [$profile->profile_picture]) : [],
            'bio' => $profile?->bio,
            'university_id' => $profile?->university_id,
            'matric_no' => $profile?->matric_no,
            'department' => $profile?->department,
            'graduation_year' => $profile?->graduation_year,
            'cgpa' => $profile?->cgpa,
            'state_id' => $profile?->state_id,
            'lga_id' => $profile?->lga_id,
            'skills' => $skillsData,
        ]);
    }

    public function form(Schema $form): Schema
    {
        $user = Auth::user();
        $profile = $user->profile;

        $schema = $this->isEditing
            ? [
                Grid::make(3)
                    ->schema([
                        // Left column (Academic details, Bio, Skills)
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Academic & Biographical Information')
                                    ->description('Update your university details and biography.')
                                    ->schema([
                                        Textarea::make('bio')
                                            ->label('Biography')
                                            ->rows(4)
                                            ->placeholder('Tell us about yourself, your goals, and interests...')
                                            ->columnSpanFull(),
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('university_id')
                                                    ->label('University')
                                                    ->options(University::all()->pluck('name', 'id'))
                                                    ->searchable()
                                                    ->required()
                                                    ->placeholder('Select your university'),
                                                TextInput::make('matric_no')
                                                    ->label('Matric Number')
                                                    ->required()
                                                    ->placeholder('e.g., UI/2026/0405'),
                                                TextInput::make('department')
                                                    ->label('Department')
                                                    ->required()
                                                    ->placeholder('e.g., Computer Science'),
                                                Select::make('graduation_year')
                                                    ->options(array_combine(range(date('Y') - 5, date('Y') + 10), range(date('Y') - 5, date('Y') + 10)))
                                                    ->required()
                                                    ->placeholder('Select year'),
                                                TextInput::make('cgpa')
                                                    ->label('CGPA')
                                                    ->numeric()
                                                    ->inputMode('decimal')
                                                    ->required()
                                                    ->placeholder('e.g., 4.50'),
                                            ]),
                                    ]),

                                Section::make('Skills & Expertise')
                                    ->description('List your skills and your proficiency level.')
                                    ->schema([
                                        Repeater::make('skills')
                                            ->schema([
                                                Select::make('skill_id')
                                                    ->label('Skill')
                                                    ->options(Skill::all()->pluck('name', 'id'))
                                                    ->required()
                                                    ->searchable()
                                                    ->distinct()
                                                    ->placeholder('Select skill'),
                                                Select::make('level')
                                                    ->options([
                                                        'Beginner' => 'Beginner',
                                                        'Intermediate' => 'Intermediate',
                                                        'Advanced' => 'Advanced',
                                                        'Expert' => 'Expert',
                                                    ])
                                                    ->required()
                                                    ->placeholder('Select level'),
                                            ])
                                            ->columns(2)
                                            ->createItemButtonLabel('Add Skill')
                                            ->grid(2),
                                    ]),
                            ]),

                        // Right column (Avatar and Location)
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Profile Picture')
                                    ->schema([
                                        FileUpload::make('profile_picture')
                                            ->label('')
                                            ->image()
                                            ->avatar()
                                            ->imageEditor()
                                            ->disk('public')
                                            ->directory('profile-pictures')
                                            ->alignCenter(),
                                    ]),

                                Section::make('Location of Origin')
                                    ->schema([
                                        Select::make('state_id')
                                            ->label('State')
                                            ->options(State::all()->pluck('name', 'id'))
                                            ->live()
                                            ->afterStateUpdated(fn (callable $set) => $set('lga_id', null))
                                            ->placeholder('Select state'),
                                        Select::make('lga_id')
                                            ->label('LGA')
                                            ->options(fn (callable $get) => Lga::where('state_id', $get('state_id'))->pluck('name', 'id'))
                                            ->placeholder('Select LGA'),
                                    ]),
                            ]),
                    ]),
            ]
            : [
                Grid::make(3)
                    ->schema([
                        // Left column
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Academic & Biographical Information')
                                    ->schema([
                                        Placeholder::make('bio')
                                            ->label('Biography')
                                            ->content($profile?->bio ?? 'No biography provided.')
                                            ->columnSpanFull(),
                                        Grid::make(2)
                                            ->schema([
                                                Placeholder::make('university')
                                                    ->label('University')
                                                    ->content($profile?->university?->name ?? '-'),
                                                Placeholder::make('matric_no')
                                                    ->label('Matric Number')
                                                    ->content($profile?->matric_no ?? '-'),
                                                Placeholder::make('department')
                                                    ->label('Department')
                                                    ->content($profile?->department ?? '-'),
                                                Placeholder::make('graduation_year')
                                                    ->label('Graduation Year')
                                                    ->content($profile?->graduation_year ?? '-'),
                                                Placeholder::make('cgpa')
                                                    ->label('CGPA')
                                                    ->content($profile?->cgpa ?? '-'),
                                            ]),
                                    ]),

                                Section::make('Skills & Expertise')
                                    ->schema([
                                        Placeholder::make('skills_badges')
                                            ->label('')
                                            ->content(fn () => new \Illuminate\Support\HtmlString(
                                                $user->skills->map(fn ($skill) => '<span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 ring-1 ring-inset ring-amber-600/20 mr-2 mb-2" style="margin-right: 8px; margin-bottom: 8px; display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; background-color: rgb(254 243 199); color: rgb(180 83 9);">' . e($skill->name) . ' (' . e($skill->pivot->level) . ')</span>')->join('') ?: 'No skills added yet.'
                                            )),
                                    ]),
                            ]),

                        // Right column
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Profile Picture')
                                    ->schema([
                                        Placeholder::make('avatar')
                                            ->label('')
                                            ->content(fn () => ($profile?->profile_picture && \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->profile_picture)) ? new \Illuminate\Support\HtmlString('<div class="flex justify-center" style="display: flex; justify-content: center;"><img src="/storage/' . $profile->profile_picture . '" class="w-32 h-32 object-cover rounded-full shadow-md border-2 border-white dark:border-gray-800" style="width: 128px; height: 128px; border-radius: 50%; object-fit: cover;" /></div>') : new \Illuminate\Support\HtmlString('<div class="flex justify-center" style="display: flex; justify-content: center;"><div class="w-32 h-32 rounded-full bg-amber-50 dark:bg-amber-950/30 border-2 border-dashed border-amber-200 dark:border-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400" style="width: 128px; height: 128px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: rgb(254 243 199); border: 2px dashed rgb(253 230 138);"><svg class="w-12 h-12" style="width: 48px; height: 48px; color: rgb(217 119 6);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div></div>')),
                                    ]),

                                Section::make('Location of Origin')
                                    ->schema([
                                        Placeholder::make('state')
                                            ->label('State')
                                            ->content($profile?->state?->name ?? '-'),
                                        Placeholder::make('lga')
                                            ->label('LGA')
                                            ->content($profile?->lga?->name ?? '-'),
                                    ]),
                            ]),
                    ]),
            ];

        return $form
            ->schema($schema)
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        if (! $this->isEditing) {
            return [
                Action::make('edit')
                    ->label('Edit Profile')
                    ->icon('heroicon-m-pencil-square')
                    ->action(function () {
                        $this->isEditing = true;
                        $this->fillForm();
                    }),
            ];
        }

        return [];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
            Action::make('cancel')
                ->label('Cancel')
                ->color('gray')
                ->action(function () {
                    $this->isEditing = false;
                    $this->mount();
                }),
        ];
    }

    public function save(): void
    {
        $user = Auth::user();
        $data = $this->form->getState();

        $profile = $user->profile;

        if (! $profile) {
            $profile = $user->profile()->create();
        }

        $profile->update([
            'profile_picture' => is_array($data['profile_picture']) ? (collect($data['profile_picture'])->first() ?? null) : $data['profile_picture'],
            'bio' => $data['bio'],
            'university_id' => $data['university_id'],
            'matric_no' => $data['matric_no'],
            'department' => $data['department'],
            'graduation_year' => $data['graduation_year'],
            'cgpa' => $data['cgpa'],
            'state_id' => $data['state_id'],
            'lga_id' => $data['lga_id'],
        ]);

        // Sync skills
        $skillsSyncData = [];
        if (isset($data['skills'])) {
            foreach ($data['skills'] as $skillItem) {
                if ($skillItem['skill_id']) {
                    $skillsSyncData[$skillItem['skill_id']] = ['level' => $skillItem['level']];
                }
            }
        }
        $user->skills()->sync($skillsSyncData);

        $this->isEditing = false;

        Notification::make()
            ->title('Profile updated successfully!')
            ->success()
            ->send();
    }
}
