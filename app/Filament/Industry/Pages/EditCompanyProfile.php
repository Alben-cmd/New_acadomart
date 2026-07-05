<?php

namespace App\Filament\Industry\Pages;

use App\Models\State;
use App\Models\Lga;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class EditCompanyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Edit Company Profile';

    protected string $view = 'filament.industry.pages.edit-company-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $company = $user->company;

        $this->form->fill([
            'name' => $company?->name,
            'logo_path' => $company?->logo_path ? (is_array($company->logo_path) ? $company->logo_path : [$company->logo_path]) : [],
            'website_url' => $company?->website_url,
            'description' => $company?->description,
            'industry_sector' => $company?->industry_sector,
            'state_id' => $company?->state_id,
            'lga_id' => $company?->lga_id,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Company Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->placeholder('Company Name'),
                                TextInput::make('industry_sector')
                                    ->placeholder('e.g., Technology, Finance'),
                                FileUpload::make('logo_path')
                                    ->label('Logo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('company-logos')
                                    ->columnSpan(2),
                                TextInput::make('website_url')
                                    ->label('Website URL')
                                    ->url()
                                    ->placeholder('https://company.example.com'),
                                Textarea::make('description')
                                    ->rows(4)
                                    ->columnSpan(2),
                            ]),
                    ]),

                Section::make('Location Details')
                    ->schema([
                        Grid::make(2)
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
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $user = Auth::user();
        $data = $this->form->getState();

        $company = $user->company;

        if (! $company) {
            $company = $user->company()->create([
                'name' => $data['name'] ?? ($user->name . ' Company'),
            ]);
        }

        $company->update([
            'name' => $data['name'],
            'logo_path' => is_array($data['logo_path']) ? (collect($data['logo_path'])->first() ?? null) : $data['logo_path'],
            'website_url' => $data['website_url'],
            'description' => $data['description'],
            'industry_sector' => $data['industry_sector'],
            'state_id' => $data['state_id'],
            'lga_id' => $data['lga_id'],
        ]);

        Notification::make()
            ->title('Company profile updated successfully!')
            ->success()
            ->send();

        $this->redirect(CompanyProfile::getUrl());
    }
}
