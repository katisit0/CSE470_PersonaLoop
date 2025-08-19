@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-6">

    @if ($errors->any())
        <div class="mb-4 font-medium text-sm text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('success') }}
        </div>
    @endif

    

   
    
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Display the user's streak -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <h3 class="text-lg font-semibold mb-4">Your Streak</h3>
            <p class="text-xl font-bold">
                Streak: {{ $user->streak_days ?? 0 }} days
            </p>
        </div>
    </div>


    <!-- Display User XP -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <h3 class="text-lg font-semibold mb-2">Your XP</h3>
            <p class="text-xl font-bold">{{ $userXp }} XP</p>
        </div>
    </div>

    <!-- Personas -->
    <div class="bg-white shadow sm:rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">Personas</h2>
        @if($user->is_profile_public || auth()->id() === $user->id)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($user->personas as $persona)
                    <div class="border rounded p-3">
                        <h3 class="font-bold">{{ $persona->name }}</h3>
                        <!-- ✅ Only show persona level -->
                        <p>Level: {{ $persona->level ?? 1 }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p>Profile is private. Persona details are hidden.</p>
        @endif
    </div>



    {{-- Achievements --}}
    <div class="bg-white shadow sm:rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">Achievements</h2>
        @if($user->achievements->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($user->achievements as $ach)
                    <div class="border p-2 rounded text-center">
                        @if($ach->icon)
                            <img src="{{ $ach->icon }}" alt="{{ $ach->name }}" class="mx-auto h-12 w-12">
                        @endif
                        <p>{{ $ach->name }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p>No achievements unlocked yet.</p>
        @endif
    </div>

    

    {{-- Quests Placeholder --}}
    <div class="bg-white shadow sm:rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">Quests</h2>
        <p>Feature coming soon...</p>
    </div>

    {{-- Journals Placeholder --}}
    <div class="bg-white shadow sm:rounded-lg p-6">
        @if($pastJournals->isEmpty())
        <p class="text-gray-400">You have no past journals.</p>
        @else
            <div class="space-y-8">
                @foreach ($pastJournals as $journal)
                    <div class="bg-[#004747] p-6 rounded-lg shadow">
                        <div class="text-sm text-gray-400 mb-2">
                            {{ $journal->created_at->format('F j, Y') }}
                        </div>
                        <div class="whitespace-pre-wrap text-white">
                            {{ $journal->content }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>


    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>
@endsection
