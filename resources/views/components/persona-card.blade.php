@props(['name', 'description', 'image', 'xp', 'id', 'showActions'])

<div class="rounded-lg shadow-sm">
    <div
        class="flex flex-col items-center bg-[#470000] hover:bg-[#FF0000] rounded-lg shadow-xl p-4 text-left transition duration-300 border-2 transform hover:-translate-y-1 ring-2 ring-offset-2 ring-offset-[#470000] ring-[#470000]"
    >
        <img
            class="w-24 h-24 mb-3 rounded-full shadow-lg"
            src="{{ $image }}"
            alt="{{ $name }} image"
        />
        <h5 class="text-xl font-semibold mb-2 text-white">{{ $name }}</h5>
        <span class="text-xl font-semibold mb-2 text-white">{{ $description }}</span>

        @if (!is_null($xp))
            <span class="text-sm text-gray-500 dark:text-gray-400">XP: {{ $xp }}</span>
        @endif

        <div class="flex mt-4 md:mt-6 justify-center w-full">
            @if($showActions)
                <button
                    type="button"
                    class="persona-btn inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#004747] rounded-lg hover:bg-[#006666] transition"
                    data-persona-id="{{ $id }}"
                    data-persona-name="{{ $name }}"
                >
                    Select {{ $name }}
                </button>
            @else
                <a
                    href="{{ url('/select-persona') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#004747] rounded-lg"
                >
                    Haven’t selected a Persona yet? Select one now
                </a>
            @endif
        </div>
    </div>
</div>
