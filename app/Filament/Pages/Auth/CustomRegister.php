<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserRole;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class CustomRegister extends BaseRegister
{
    /**
     * Build the registration form.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getRoleFormComponent(),
            ]);
    }

    /**
     * Get the role form component.
     */
    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->label('Register As')
            ->options([
                UserRole::Student->value => 'Student',
                UserRole::Industry->value => 'Industry/Employer',
                UserRole::Researcher->value => 'Researcher',
            ])
            ->required()
            ->default(UserRole::Student->value);
    }
}
