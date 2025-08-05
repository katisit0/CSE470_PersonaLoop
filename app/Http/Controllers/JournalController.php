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

        // Get the selected month or default to today
        $monthParam = $request->query('month');
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::today();

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Dates with journals this month for this user
        $journalDates = Journal::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->pluck(\DB::raw('DATE(created_at)'))
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        // Determine which date's journal to show (default today)
        $selectedDateStr = $request->query('date') ?? Carbon::today()->format('Y-m-d');
        $selectedDate = Carbon::parse($selectedDateStr);

        // Get journal entry for selected date (if any)
        $journal = Journal::where('user_id', $user->id)
            ->whereDate('created_at', $selectedDate)
            ->first();

        // Check if journal can be edited/added: only if selected date is today
        $canEdit = $selectedDate->isSameDay(Carbon::today());

        // If AJAX request, return full rendered HTML (no partials)
        if ($request->ajax()) {
            return view('journal.create', compact(
                'currentMonth', 'journalDates', 'selectedDate', 'journal', 'canEdit'
            ))->render();
        }

        // Normal full page load
        return view('journal.create', compact(
            'currentMonth', 'journalDates', 'selectedDate', 'journal', 'canEdit'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:10000',
            'date' => 'required|date',
        ]);

        $user = Auth::user();

        $journalDate = Carbon::parse($request->input('date'));

        // Only allow adding journal for today
        if (!$journalDate->isSameDay(Carbon::today())) {
            return redirect()->back()->with('error', 'You can only add or edit journal for today.');
        }

        // Check if journal already exists for today (only one journal per day)
        $existing = Journal::where('user_id', $user->id)
            ->whereDate('created_at', $journalDate)
            ->first();

        if ($existing) {
            // Optionally, you could allow editing here, but you said only add once per day
            return redirect()->back()->with('error', 'You have already submitted a journal for today.');
        }

        // Create new journal entry for today
        $xp = 10; // example XP value
        Journal::create([
            'user_id' => $user->id,
            'content' => $request->content,
            'xp_awarded' => $xp,
            'created_at' => $journalDate,
            'updated_at' => $journalDate,
        ]);

        // Award XP
        $user->xp += $xp;
        $user->save();

        return redirect()->route('journal.create', ['month' => $journalDate->format('Y-m'), 'date' => $journalDate->format('Y-m-d')])
            ->with('success', 'Journal submitted and XP awarded!');
    }
}
