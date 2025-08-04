@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8 p-6 bg-[#470000] rounded-lg shadow-xl p-4 text-left transition duration-300 border-2 transform hover:-translate-y-1 ring-2 ring-offset-2 ring-offset-[#470000] ring-[#470000]">
    @if ($persona)
        <div class="text-center pt-6">
            <h1 class="text-3xl font-bold text-white">{{ $persona->name }}</h1>
            <img src="{{ asset($persona->image) }}" 
                alt="{{ $persona->name }}" 
                style="width: 120px; height: 120px; object-fit: contain; border-radius: 0.25rem;"
                class="rounded mx-auto block"
            >
            <p class="text-xl font-semibold mb-2 text-white">{{ $persona->description }}</p>
            <p class="text-xl font-semibold mb-2 text-white">XP: {{ $persona->pivot->xp }}</p>
        </div>
    @else
        <p class="text-center text-gray-500">You haven't selected a persona for today yet.</p>
    @endif
</div>
<div class="max-w-4xl mx-auto text-center mt-4 bg-[#470000] rounded-lg shadow-xl p-4 text-left transition duration-300 border-2 transform hover:-translate-y-1 ring-2 ring-offset-2 ring-offset-[#470000] ring-[#470000]">
    <a href="{{ url('/select-persona') }}" class="text-white hover:text-[#3E4305] text-lg">
        Haven't selected a persona today? Select now
    </a>
</div>
@endsection
