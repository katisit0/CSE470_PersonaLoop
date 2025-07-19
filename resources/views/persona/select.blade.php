@extends('layouts.app')

@section('content')


<div class="min-h-screen flex items-center justify-center bg-gray-900 text-white overflow-hidden">
    <div class="relative w-[400px] h-[400px] hand">
        @foreach ($personas as $index => $persona)
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 card" style="--i: {{ $index }};">
                <form action="{{ route('persona.select', $persona->id) }}" method="POST" class="w-full h-full">
                    @csrf
                    <button type="submit" class="w-24 h-36 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md hover:scale-105 transition duration-300 flex items-center justify-center text-center font-bold">
                        {{ $persona->name }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('styles')
<style>
    .hand {
        position: relative;
        perspective: 1000px;
        width: 400px;
        height: 400px;
    }
    .card {
        position: absolute;
        top: 50%;
        left: 50%;
        transform-origin: bottom center;
        transition: transform 0.4s;
    }
    .hand:hover .card {
        transform: rotate(calc((var(--i) - 2) * 15deg)) translateY(-30px);
    }
</style>
@endsection
