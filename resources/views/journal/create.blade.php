@extends('layouts.app')

@section('content')

@php
    use Carbon\Carbon;

    $today = Carbon::today();
    $currentMonth = $currentMonth ?? (request('month') ? Carbon::parse(request('month') . '-01') : $today);

    $startOfMonth = $currentMonth->copy()->startOfMonth();
    $endOfMonth = $currentMonth->copy()->endOfMonth();
    $startDay = $startOfMonth->copy()->startOfWeek();
    $endDay = $endOfMonth->copy()->endOfWeek();

    $selectedDate = $selectedDate ?? (request('date') ? Carbon::parse(request('date')) : $today);

    $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
@endphp

{{-- NAVIGATION BAR OUTSIDE AJAX REPLACED CONTENT --}}
<div class="max-w-5xl mx-auto mt-6 px-4">
    {{-- Your main navbar here --}}
    {{-- For example --}}
    <nav class="bg-gray-900 text-white p-4 rounded mb-6">
        <h1 class="text-3xl font-bold">My Journal App</h1>
    </nav>
</div>

{{-- THIS DIV WILL BE REPLACED VIA AJAX --}}
<div class="max-w-3xl mx-auto mt-10 px-4 text-white" id="journal-calendar-container">

@section('calendarContent')
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
                    $isToday = $date->isSameDay($today);
                    $isSelected = $date->isSameDay($selectedDate);
                @endphp

                <td class="border border-gray-700 text-center p-3 relative
                    {{ $isCurrentMonth ? 'text-white bg-gray-900' : 'text-gray-500 bg-gray-700' }}
                    {{ $isSelected ? 'ring-2 ring-blue-400 rounded' : '' }}"
                    data-date="{{ $dateStr }}"
                >
                    <span class="cursor-pointer block w-full h-full relative z-10 date-cell">
                        {{ $date->day }}
                    </span>

                    {{-- Circles --}}
                    @if ($hasJournal)
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full border-2 border-red-500"></span>
                    @endif

                    @if ($isToday)
                        <span class="absolute bottom-1 left-1 w-4 h-4 rounded-full border-2 border-green-500"></span>
                    @endif
                </td>

                @if ($date->dayOfWeek === 6)
                    </tr>
                @endif
            @endfor
        </tbody>
    </table>

    {{-- Journal Form --}}
    <h1 class="text-3xl font-bold mb-4">Journal for {{ $selectedDate->format('F j, Y') }}</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-700 rounded shadow">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-700 rounded shadow">{{ session('error') }}</div>
    @endif

    {{-- Only allow adding journal if date is today and journal doesn't exist --}}
    @php
        $canAddJournal = $selectedDate->isSameDay($today) && !$journal;
    @endphp

    @if($canAddJournal)
    <form method="POST" action="{{ route('journal.store') }}" id="journal-form">
        @csrf

        <input type="hidden" name="date" value="{{ $selectedDate->format('Y-m-d') }}">

        <textarea
            id="journal-content"
            name="content"
            rows="10"
            placeholder="Write about your day, your thoughts, or anything you like..."
            class="bg-[#222] text-white border border-gray-600 rounded-lg resize-none w-full p-3"
            required
        >{{ old('content') }}</textarea>

        @error('content')
            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror

        <button type="submit" class="mt-4 inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200">
            Publish Journal
        </button>
    </form>
    @elseif($journal)
        <div class="p-4 bg-gray-900 rounded border border-gray-700 whitespace-pre-wrap">{{ $journal->content }}</div>
    @else
        <p class="italic text-gray-400">No journal entry for this date.</p>
    @endif
</div>
@endsection

{{-- AJAX + JS --}}
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('journal-calendar-container');

    // Helper to fetch and load month/date
    function loadView(params = {}) {
        const url = new URL(window.location);
        if (params.month) url.searchParams.set('month', params.month);
        if (params.date) url.searchParams.set('date', params.date);
        else url.searchParams.delete('date');

        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;
            attachListeners(); // reattach listeners on new content
        })
        .catch(console.error);
    }

    // Navigation buttons logic: calculate prev/next months dynamically
    function getCurrentMonthFromHeader() {
        const header = document.getElementById('calendar-month-year').textContent.trim();
        return new Date(header + ' 1'); // parse e.g. "August 2025 1"
    }

    function formatYearMonth(date) {
        return date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
    }

    function prevMonth() {
        const current = getCurrentMonthFromHeader();
        current.setMonth(current.getMonth() - 1);
        loadView({month: formatYearMonth(current)});
    }

    function nextMonth() {
        const current = getCurrentMonthFromHeader();
        current.setMonth(current.getMonth() + 1);
        loadView({month: formatYearMonth(current)});
    }

    function today() {
        const today = new Date();
        loadView({month: formatYearMonth(today), date: today.toISOString().slice(0,10)});
    }

    // Attach event listeners to buttons and date cells
    function attachListeners() {
        document.getElementById('prev-month').onclick = prevMonth;
        document.getElementById('next-month').onclick = nextMonth;
        document.getElementById('today-btn').onclick = today;

        // Clicking a date cell loads that day's journal but only for current month
        document.querySelectorAll('.date-cell').forEach(cell => {
            cell.onclick = () => {
                const date = cell.parentElement.getAttribute('data-date');
                const currentMonthText = document.getElementById('calendar-month-year').textContent.trim();
                const currentMonth = new Date(currentMonthText + ' 1');

                if (date.startsWith(currentMonth.getFullYear() + '-' + String(currentMonth.getMonth() + 1).padStart(2, '0'))) {
                    loadView({month: formatYearMonth(currentMonth), date: date});
                }
            };
        });

        // Intercept form submission to use AJAX
        const form = document.getElementById('journal-form');
        if (form) {
            form.onsubmit = e => {
                e.preventDefault();
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': formData.get('_token') },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        today(); // reload today's journal/month to update UI
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(() => alert('Failed to submit journal.'));
            };
        }
    }

    attachListeners();
});
</script>
@endsection
