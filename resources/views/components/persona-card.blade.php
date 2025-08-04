<div class="rounded-lg shadow-sm">
    <div class="flex flex-col items-center persona-btn bg-[#470000] hover:bg-[#FF0000] rounded-lg shadow-xl p-4 text-left transition duration-300 border-2 transform hover:-translate-y-1 ring-2 ring-offset-2 ring-offset-[#470000] ring-[#470000]">
        <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $image }}" alt="{{ $name }} image"/>
        <h5 class="text-xl font-semibold mb-2 text-white">{{ $name }}</h5>
        <span class="text-xl font-semibold mb-2 text-white">{{ $description }}</span>
        @if (!is_null($xp))
            <span class="text-sm text-gray-500 dark:text-gray-400">XP: {{ $xp }}</span>
        @endif
        <div class="flex mt-4 md:mt-6">
            @if($showActions)
                {{-- 
                <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add friend</a>
                <a href="#" class="py-2 px-4 ms-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Message</a>
                --}}
                @else
                <a href="{{ url('/select-persona') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#004747] rounded-lg">
                    Haven’t selected a Persona yet? Select one now
                </a>
            @endif
        </div>
    </div>
</div>
