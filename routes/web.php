<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
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
    }
    return view('welcome');
});

Route::get('/login-redirect', function () {
    return redirect()->to('/login');
})->name('login');
