<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Persona;
use App\Models\UserPersona;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get the persona selected *today*
        $persona = $user->selectedPersona();

        $userXp = $user->xp ?? 0;
        return view('dashboard', compact('persona','userXp'));
        
    }
}