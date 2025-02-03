@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-xl font-semibold">Events</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($events as $event)
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h2 class="text-lg font-bold">{{ $event->title }}</h2>
                    <p class="text-sm text-gray-600">{{ $event->date }}</p>
                    <p class="mt-2">{{ $event->description }}</p>
                    <a href="{{ route('events.register', $event->id) }}" class="mt-4 block text-center bg-blue-500 text-white py-2 px-4 rounded">Register</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
