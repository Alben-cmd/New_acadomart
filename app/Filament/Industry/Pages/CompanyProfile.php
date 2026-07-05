<?php

namespace App\Filament\Industry\Pages;

use App\Models\State;
use App\Models\Lga;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class CompanyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Company Profile';

    protected static ?string $title = 'Company Profile';

    protected string $view = 'filament.industry.pages.company-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $company = $user->company;

        if (! $company) {
            $user->company()->create([
                'name' => $user->name . ' Company',
            ]);
        }
    }

    public function form(Schema $form): Schema
    {
        $user = Auth::user();
        $company = $user->company;

        return $form
            ->schema([
                Section::make('Company Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('logo_path')
                                    ->label('Logo')
                                    ->content(fn () => ($company?->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($company->logo_path)) ? new \Illuminate\Support\HtmlString('<img src="/storage/' . $company->logo_path . '" class="w-20 h-20 object-contain rounded-lg" style="width: 80px; height: 80px;" />') : 'No Logo')
                                    ->columnSpan(2),
                                Placeholder::make('name')
                                    ->label('Company Name')
                                    ->content($company?->name),
                                Placeholder::make('industry_sector')
                                    ->label('Industry Sector')
                                    ->content($company?->industry_sector ?? '-'),
                                Placeholder::make('website_url')
                                    ->label('Website URL')
                                    ->content(fn () => $company?->website_url ? new \Illuminate\Support\HtmlString('<a href="' . $company->website_url . '" target="_blank" class="text-amber-600 hover:underline dark:text-amber-400">' . $company->website_url . '</a>') : '-'),
                                Placeholder::make('description')
                                    ->label('Description')
                                    ->content($company?->description ?? '-')
                                    ->columnSpan(2),
                            ]),
                    ]),

                Section::make('Location Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('state')
                                    ->label('State')
                                    ->content($company?->state?->name ?? '-'),
                                Placeholder::make('lga')
                                    ->label('LGA')
                                    ->content($company?->lga?->name ?? '-'),
                            ]),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Edit Profile')
                ->icon('heroicon-m-pencil-square')
                ->url(fn (): string => EditCompanyProfile::getUrl()),
        ];
    }
}
