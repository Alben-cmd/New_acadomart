<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class CustomLoginResponse implements LoginResponseContract
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
