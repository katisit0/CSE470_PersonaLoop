<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Persona;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

 
        $selectedPersona = $user->personas()
            ->whereDate('user_persona.created_at', $today)
            ->first();

        return view('dashboard', compact('selectedPersona'));
    }
}
