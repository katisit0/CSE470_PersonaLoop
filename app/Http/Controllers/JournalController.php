<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        // Fetch all journals by this user, ordered descending by date
        $pastJournals = Journal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('journal.create', compact('pastJournals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:10000',
        ]);

        $user = Auth::user();

        // Check if user already submitted today
        $alreadySubmitted = Journal::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->back()->with('error', 'You have already submitted a journal today.');
        }

        // Save journal and award XP
        $xp = 15; 
        Journal::create([
            'user_id' => $user->id,
            'content' => $request->content,
            'xp_awarded' => $xp,
        ]);

        // Award XP to user
        $user->increment('xp', $xp);

        return redirect()->route('journal.create')->with('success', 'Journal submitted and XP awarded!');
    }
}
