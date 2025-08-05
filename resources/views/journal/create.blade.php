@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 text-white px-4">
    <h1 class="text-3xl font-bold mb-6">Write Your Journal for Today</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-700 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-700 rounded shadow">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('journal.store') }}">
        @csrf

        <x-journal.textarea
            id="journal"
            name="content"
            rows="10"
            placeholder="Write about your day, your thoughts, or anything you like..."
            class="bg-[#222] text-white border border-gray-600 rounded-lg resize-none"
            required
        >
            {{ old('content') }}
        </x-journal.textarea>

        @error('content')
            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror

        
    </form>
</div>
@endsection
