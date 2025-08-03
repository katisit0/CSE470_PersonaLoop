<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($selectedPersona)
                        <div>
                            <h2 class="text-2xl font-bold mb-4">Today's Selected Persona</h2>
                            <p><strong>Name:</strong> {{ $selectedPersona->name }}</p>
                            <p><strong>Description:</strong> {{ $selectedPersona->description }}</p>
                            <p><strong>XP:</strong> {{ $selectedPersona->pivot->xp ?? 0 }}</p>
                        </div>
                    @else
                        <div>
                            <h2 class="text-2xl font-bold mb-4">Choose Your Persona</h2>
                            <form method="POST" action="{{ route('persona.select.store') }}">
                                
                                @csrf
                                <select name="persona_id" required class="mb-4 block w-full p-2 rounded bg-gray-700 text-white">
                                    @foreach (\App\Models\Persona::all() as $persona)
                                        <option value="{{ $persona->id }}">{{ $persona->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Select Persona
                                </button>
                            </form>
                            
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
