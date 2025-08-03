@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 text-white py-12 px-6">
    <h1 class="text-3xl font-bold mb-8 text-center">Choose Your Persona!</h1>

    {{-- Message Container --}}
    <div id="message" class="max-w-2xl mx-auto mb-6 text-center font-semibold text-lg"></div>

    <div class="max-w-7xl mx-auto">
        <div class="flex flex-wrap">
            @foreach ($personas as $persona)
                <div class="basis-1/3 max-w-1/3 p-4">
                    <button 
                        class="w-full persona-btn bg-[#470000] hover:bg-[#FF0000] rounded-lg shadow-xl p-4 text-left transition duration-300 
                        border-2 transform hover:-translate-y-1 ring-2 ring-offset-2 ring-offset-[#470000] ring-[#470000]"
                        data-persona-id="{{ $persona->id }}"
                        data-persona-name="{{ $persona->name }}" {{-- modify with other persona attributes if needed --}}
                        
                    >
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
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- jQuery AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('.persona-btn').click(function (e) {
        e.preventDefault();


        // modify with other persona attributes if needed
        const personaId = $(this).data('persona-id');
        const personaName = $(this).data('persona-name');

        if (!confirm(`Select ${personaName}?`)) return;

        $.ajax({
            url: '/select-persona',
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                persona_id: personaId
            },
            success: function (response) {
                if (response.success) {
                    $('#message').text(response.message).css('color', 'lime');
                } else {
                    $('#message').text(response.message).css('color', 'orange');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                $('#message').text(msg).css('color', 'red');
            }

        });
    });
</script>
@endsection
