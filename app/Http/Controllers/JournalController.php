<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JournalController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();

        $monthParam = $request->query('month');
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::today();

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $journalDates = Journal::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->pluck(\DB::raw('DATE(created_at)'))
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        // Determine selected date: prefer 'date' query param, else today
        $selectedDate = $request->query('date') ? Carbon::parse($request->query('date')) : Carbon::today();

        // Get journal for selected date if exists
        $selectedJournal = Journal::where('user_id', $user->id)
            ->whereDate('created_at', $selectedDate)
            ->first();

        // If AJAX, return same view (journal.create) but only the calendar container HTML to replace
        if ($request->ajax()) {
            // Return only the part inside #journal-calendar-container (blade will need to support this)
            // We can detect ajax in blade and render only container or full page
            return view('journal.create', compact('currentMonth', 'journalDates', 'selectedDate', 'selectedJournal'));
        }

        // For normal request return full page
        return view('journal.create', compact('currentMonth', 'journalDates', 'selectedDate', 'selectedJournal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:10000',
        ]);

        $user = Auth::user();

        $today = Carbon::today();

        // Force journalDate to today only, ignore any date from input
        $journalDate = $today;

        // Check if journal already exists for today
        $alreadySubmitted = Journal::where('user_id', $user->id)
            ->whereDate('created_at', $journalDate)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->back()->with('error', 'You have already submitted a journal for today.');
        }

        // Save new journal
        $xp = 10;
        Journal::create([
            'user_id' => $user->id,
            'content' => $request->content,
            'xp_awarded' => $xp,
            'created_at' => $journalDate,
            'updated_at' => $journalDate,
        ]);

        $user->xp += $xp;
        $user->save();

        return redirect()->route('journal.create', ['month' => $journalDate->format('Y-m'), 'date' => $journalDate->format('Y-m-d')])
                        ->with('success', 'Journal submitted and XP awarded!');
    }

}
