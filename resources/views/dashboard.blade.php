@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 mt-16">
    <div class="flex justify-center">
        <x-persona-card 
            :name="$persona->name"
            :description="$persona->description"
            :image="$persona->image"
            :xp="$persona->pivot->xp"
            :showActions="false"
        />
    </div>
</div>
@endsection
