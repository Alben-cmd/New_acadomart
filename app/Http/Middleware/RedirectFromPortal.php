<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectFromPortal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
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

        return $next($request);
    }
}
