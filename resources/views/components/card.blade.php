@props(['title' => 'Card Title', 'description' => 'Card description goes here.', 'image' => null])

<div {{ $attributes->merge(['class' => 'rounded-lg shadow-xl p-6 bg-[#470000] border-2 border-[#470000] ring-2 ring-offset-2 ring-offset-[#470000] ring-[#470000] text-white transition duration-300 hover:bg-[#FF0000] hover:-translate-y-1 transform']) }}>
    @if($image)
        <figure class="px-6 pt-6">
            <img src="{{ $image }}" alt="{{ $title }}" class="rounded-xl shadow-lg mx-auto" />
        </figure>
    @endif

    <div class="card-body text-center mt-4">
        <h2 class="card-title text-2xl font-semibold mb-2">{{ $title }}</h2>
        <p class="mb-4">{{ $description }}</p>

        <div class="card-actions justify-center">
            {{ $slot }}
        </div>
    </div>
</div>
