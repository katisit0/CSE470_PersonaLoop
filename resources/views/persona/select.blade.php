@extends('layouts.app')

@section('content')
<div class="min-h-screen text-white py-12 px-6">

    <div class="flex justify-center mb-8">
        <div class="relative bg-[#FF0000] p-6 rounded-2xl shadow-lg border border-teal-300">
            <h1 class="text-4xl font-extrabold text-white text-center tracking-wide drop-shadow-md">
                Choose Your Persona!
            </h1>
        </div>
    </div>


    {{-- Message Container --}}
    <div id="message" class="max-w-2xl mx-auto mb-6 text-center font-semibold text-lg"></div>

    {{-- Persona Cards in a responsive row of 3 --}}
    <div class="flex flex-wrap justify-center gap-6">
        @foreach ($personas as $persona)
            <div class="flex-shrink-0 w-full sm:w-1/2 lg:w-1/3">
                <x-persona-card 
                    :name="$persona->name"
                    :description="$persona->description"
                    :image="$persona->image"
                    :xp="null"
                    :id="$persona->id"
                    :showActions="true"
                />
            </div>
        @endforeach
    </div>

</div>

{{-- jQuery AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.persona-btn', function (e) {
        e.preventDefault();

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
                $('#message')
                    .text(response.message)
                    .css('color', response.success ? 'lime' : 'orange');
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                $('#message').text(msg).css('color', 'red');
            }
        });
    });
</script>
@endsection
