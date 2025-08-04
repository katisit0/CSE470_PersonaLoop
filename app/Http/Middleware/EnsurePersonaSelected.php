<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPersona;
use Carbon\Carbon;

class EnsurePersonaSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::check()) {
            return $next($request);
        }

        
        $user = Auth::user();

        $today = Carbon::today()->toDateString();

        $hasSelectedToday = $user->personas()
            ->wherePivot('date', $today)
            ->exists();

        if (!$hasSelectedToday && $request->route()->getName() !== 'persona.select') {
            return redirect()->route('persona.select');
        }

        return $next($request);
    }
}
