@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;

    $today = Carbon::today();

    $selectedDate = $selectedDate ?? $today;
    $selectedJournal = $selectedJournal ?? null;

    $isToday = $selectedDate->isSameDay($today);

    // Month currently displayed (already passed from controller)
    // but if you want to be safe:
    $currentMonth = $currentMonth ?? $today->copy()->startOfMonth();

    // Calculate calendar grid start and end days for full weeks
    $startOfMonth = $currentMonth->copy()->startOfMonth();
    $endOfMonth = $currentMonth->copy()->endOfMonth();

    $startDay = $startOfMonth->copy()->startOfWeek();
    $endDay = $endOfMonth->copy()->endOfWeek();

    $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

    $hasJournalForSelectedDate = $selectedJournal !== null;
@endphp


<div class="max-w-3xl mx-auto mt-10 px-4 text-white" id="journal-calendar-container">

    {{-- Navigation --}}
    <div class="flex items-center justify-between mb-6">
        <button id="prev-month" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 transition">&laquo; Prev</button>

        <h2 id="calendar-month-year" class="text-2xl font-bold cursor-default">{{ $currentMonth->format('F Y') }}</h2>

        <button id="next-month" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 transition">Next &raquo;</button>

        <button id="today-btn" class="ml-4 px-3 py-1 bg-green-600 rounded hover:bg-green-500 transition">Today</button>
    </div>

    {{-- Calendar Table --}}
    <table class="w-full border-collapse border border-gray-600 rounded-lg shadow-lg bg-gray-800 mb-12" id="calendar-table">
        <thead>
            <tr>
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <th class="border border-gray-600 px-3 py-2 text-gray-300">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @for($date = $startDay->copy(); $date <= $endDay; $date->addDay())
                @if ($date->dayOfWeek === 0)
                    <tr>
                @endif

                @php
                    $isCurrentMonth = $date->month === $currentMonth->month;
                    $dateStr = $date->format('Y-m-d');
                    $hasJournal = in_array($dateStr, $journalDates ?? []);
                    $isTodayDate = $date->isSameDay($today);
                    $isSelectedDate = $date->isSameDay($selectedDate);
                @endphp

                <td class="border border-gray-700 text-center p-3 relative cursor-pointer
                    {{ $isCurrentMonth ? 'text-white bg-gray-900' : 'text-gray-500 bg-gray-700' }}
                    {{ $isSelectedDate ? 'ring-4 ring-blue-400' : '' }}"
                    data-date="{{ $dateStr }}">
                    
                    <span class="block w-full h-full relative z-10">
                        {{ $date->day }}
                    </span>

                    {{-- Circles for journal entry and today --}}
                    @if ($hasJournal)
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full border-2 border-red-500"></span>
                    @endif

                    @if ($isTodayDate)
                        <span class="absolute bottom-1 left-1 w-4 h-4 rounded-full border-2 border-green-500"></span>
                    @endif
                </td>

                @if ($date->dayOfWeek === 6)
                    </tr>
                @endif
            @endfor
        </tbody>
    </table>

    {{-- Journal Section --}}
    <h1 class="text-3xl font-bold mb-4">
        @if ($isToday)
            Write Your Journal for Today
        @else
            Journal for {{ $selectedDate->format('F j, Y') }}
        @endif
    </h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-700 rounded shadow">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-700 rounded shadow">{{ session('error') }}</div>
    @endif

    @if($isToday && !$hasJournalForSelectedDate)
        {{-- Show form only if today and no journal exists --}}
        <form method="POST" action="{{ route('journal.store') }}" id="journal-form">
            @csrf
            {{-- No hidden date input: always today --}}
            <x-journal.textarea
                id="journal"
                name="content"
                rows="10"
                placeholder="Write about your day, your thoughts, or anything you like..."
                class="bg-[#222] text-white border border-gray-600 rounded-lg resize-none"
                required
            >{{ old('content') }}</x-journal.textarea>

            @error('content')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror

            <button type="submit" class="mt-4 inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200">
                Publish Journal
            </button>
        </form>
    @else
        {{-- Show journal content or message --}}
        @if($hasJournalForSelectedDate)
            <div class="whitespace-pre-wrap bg-[#222] p-4 rounded border border-gray-600">
                {{ $selectedJournal->content }}
            </div>
        @else
            <p class="text-gray-400 italic">No journal entry for this day.</p>
        @endif
    @endif
</div>

{{-- AJAX scripts for month navigation and selecting date --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('journal-calendar-container');
    const prevBtn = document.getElementById('prev-month');
    const nextBtn = document.getElementById('next-month');
    const todayBtn = document.getElementById('today-btn');

    function loadPage(params = {}) {
        const url = new URL(window.location);
        Object.keys(params).forEach(key => url.searchParams.set(key, params[key]));
        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;
            attachDateClickHandlers(); // re-attach click handlers after replacing content
        })
        .catch(err => console.error('Failed to load:', err));
    }

    prevBtn.addEventListener('click', () => {
        loadPage({month: "{{ $prevMonth }}"});
    });

    nextBtn.addEventListener('click', () => {
        loadPage({month: "{{ $nextMonth }}"});
    });

    todayBtn.addEventListener('click', () => {
        loadPage({month: "{{ $today->format('Y-m') }}", date: "{{ $today->format('Y-m-d') }}"});
    });

    function attachDateClickHandlers() {
        document.querySelectorAll('#calendar-table td[data-date]').forEach(td => {
            td.addEventListener('click', () => {
                const date = td.getAttribute('data-date');
                loadPage({month: date.substring(0,7), date: date});
            });
        });
    }

    attachDateClickHandlers();
});
</script>


@endsection
