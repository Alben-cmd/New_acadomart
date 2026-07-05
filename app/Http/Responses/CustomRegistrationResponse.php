<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\RegistrationResponse as RegistrationResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class CustomRegistrationResponse implements RegistrationResponseContract
{
    /**
     * Create a redirect response.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->to('/admin');
        } elseif ($user->isStudent()) {
            return redirect()->to('/student');
        } elseif ($user->isIndustry()) {
            return redirect()->to('/industry');
        } elseif ($user->isResearcher()) {
            return redirect()->to('/researcher');
        }

        return redirect()->to('/');
    }
}
