@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 mt-16">
    <div class="flex justify-center">
        @if ($persona)
            <x-persona-card 
                :name="$persona->name ?? ''"
                :description="$persona->description ?? ''"
                :image="$persona->image ?? ''"
                :xp="$persona->pivot->xp ?? 0"
                :showActions="false"
            />
        @else
            <div class="text-center text-white bg-red-600 px-6 py-4 rounded shadow-lg">
                <h2 class="text-2xl font-semibold">No Persona Selected today</h2>
                <p class="mt-2">Please <a href="{{ route('persona.select') }}" class="underline font-medium">choose a persona</a> to begin your journey.</p>
            </div>
        @endif
    </div>
</div>
@endsection
