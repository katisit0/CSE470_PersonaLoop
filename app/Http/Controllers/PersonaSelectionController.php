<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\UserPersona;
use App\Models\PersonaSelection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PersonaSelectionController extends Controller
{
    /**
     * Show all personas for selection.
     */
    public function index()
    {
        $personas = Persona::all();
        return view('persona.select', compact('personas'));
    }

    /**
     * Store the selected persona for today.
     */
    public function store(Request $request)
    {
        $request->validate([
            'persona_id' => 'required|exists:personas,id',
        ]);

        $user = Auth::user();
        $today = Carbon::today();

        // Check if user already selected a persona today
        if ($user->selectedPersona()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already selected a persona today.',
            ], 409);
        }

        // -----------------------------
        // Update or create user_persona row
        // -----------------------------
        $userPersona = UserPersona::updateOrCreate(
            [
                'user_id' => $user->id,
                'persona_id' => $request->persona_id
            ],
            [
                'is_unlocked' => true,
                'last_selected_at' => now(),
                'date' => $today,
            ]
        );

        // -----------------------------
        // STREAK CALCULATION LOGIC
        // -----------------------------
        $selections = $user->personaSelections()
                           ->orderBy('selected_at', 'desc')
                           ->pluck('selected_at')
                           ->toArray();

        $streak = 1;
        $lastDate = Carbon::parse($today)->startOfDay();

        foreach ($selections as $date) {
            $date = Carbon::parse($date)->startOfDay();
            $expectedDate = $lastDate->copy()->subDay();

            if ($date->equalTo($expectedDate)) {
                $streak++;
                $lastDate = $date;
            } else {
                break;
            }
        }

        // Save streak in user table
        $user->streak_days = $streak;
        $user->save();

        

        // -----------------------------
        // BONUS STREAK XP LOGIC
        // -----------------------------
        $baseXp = 10;
        $bonusXp = $baseXp + ($streak - 1) * 2; // +2 XP per consecutive day
        $user->increment('xp', $bonusXp);

        
        PersonaSelection::create([
            'user_id' => $user->id,
            'persona_id' => $request->persona_id,
            'selected_at' => $today,
            'xp_earned' => $bonusXp,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Persona selected successfully!',
            'streak_days' => $user->streak_days,
            'today_persona' => $user->selectedPersona(),
        ]);
    }
}
