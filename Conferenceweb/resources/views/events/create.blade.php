@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Create Event</h1>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Event Creation Form -->
    <form action="{{ route('events.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Title</label>
            <input type="text" name="title" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Description</label>
            <textarea name="description" class="w-full p-2 border rounded" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Date</label>
            <input type="date" name="date" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Create Event
        </button>
    </form>
</div>
@endsection
