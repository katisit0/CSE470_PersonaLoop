<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $alreadySelected = $user->personas()
            ->whereDate('user_persona.created_at', $today)
            ->exists();

        if ($alreadySelected) {
            return response()->json([
                'success' => false,
                'message' => 'You have already selected a persona today.',
            ], 409);
        }

        


        // Check if user has used this persona before
        $existingEntry = $user->personas()
            ->where('persona_id', $request->persona_id)
            ->first();

        if ($existingEntry) {
            // Level up logic: increment XP or level
            DB::table('user_persona')
                ->where('user_id', $user->id)
                ->where('persona_id', $request->persona_id)
                ->increment('xp', 10);                                                // Customize XP increment as needed!!!!!!!!!!!!!!
        } else {
            // Unlock new persona
            $user->personas()->attach($request->persona_id, [
                'xp' => 10, // Start with base XP
                'date' => today(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Persona selected successfully!',
        ]);


    }

    
}
