@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 text-white py-12 px-6">
    <h1 class="text-3xl font-bold mb-8 text-center">Choose Your Persona</h1>
    
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-wrap">
            @foreach ($personas as $persona)
                <div class="basis-1/3 max-w-1/3 p-4">
                    <form action="{{ route('persona.select', $persona->id) }}" method="POST" onsubmit="return confirm('Select {{ $persona->name }}?')">
                        @csrf
                        <input type="hidden" name="persona_id" value="{{ $persona->id }}">
                        <button type="submit" class="w-full bg-[#470000] hover:bg-[#FF0000] rounded-lg shadow-xl p-4 text-left transition duration-300 
                            border-2
                            transform hover:-translate-y-1 
                            ring-2 ring-offset-2 ring-offset-[#470000] ring-[#470000]">
                            
                            <div class="h-40 w-full bg-gray rounded mb-4 flex items-center justify-center">
                                <span class="text-gray-400">
                                    <img src="{{ asset($persona->image) }}" 
                                        alt="{{ $persona->name }}" 
                                        style="width: 120px; height: 120px; object-fit: contain; border-radius: 0.25rem;"
                                        class="rounded"
                                    >
                                </span>
                            </div>
                
                            <h2 class="text-xl font-semibold mb-2 text-white">{{ $persona->name }}</h2>
                            <p class="text-sm text-white-300">{{ $persona->description }}</p>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
