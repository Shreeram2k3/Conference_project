@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">
    <!-- Event Details -->
    <div class="text-center">
        <h1 class="text-3xl font-semibold">{{ $event->event_name }}</h1>
        <p class="mt-2 text-gray-600">{{ $event->description }}</p>
        <p class="text-gray-500 text-sm">Start: {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
        <p class="text-gray-500 text-sm">End: {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}</p>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
            <strong>Error!</strong> Please fix the following issues:
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Timeline Section -->
    <div class="mt-10">
        <h2 class="text-2xl font-semibold">Event Timeline</h2>

        <!-- Display existing timelines -->
        <div class="mt-6 space-y-4">
            @forelse ($timelines as $timeline)
                <div class="bg-white shadow-md rounded-lg p-4 border border-gray-300">
                    <h3 class="text-xl font-semibold">{{ $timeline->title }}</h3>
                    <p>{{ $timeline->description }}</p>
                    <p class="text-sm text-gray-500">Date: {{ \Carbon\Carbon::parse($timeline->date)->format('M d, Y') }}</p>
                </div>
            @empty
                <p class="text-gray-500">No timeline added yet.</p>
            @endforelse
        </div>

        <!-- Add New Timeline -->
        <div class="mt-10" x-data="{ showForm: {{ $errors->any() ? 'true' : 'false' }} }">
            <button @click="showForm = true" class="bg-blue-500 text-white rounded-lg px-4 py-2">Add Timeline</button>

            <!-- Floating Form Modal -->
            <div x-show="showForm" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-xl font-semibold mb-4">Add Event Timeline</h2>

                    <form method="POST" action="{{ route('timelines.store', $event->id) }}">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Event Date Constraints -->
                        <p class="text-sm text-gray-500">Event Start: {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-500 mb-4">Event End: {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}</p>

                        <!-- Timeline Date -->
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Timeline Date</label>
                            <input type="date" name="date" id="date" value="{{ old('date') }}" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                            @error('date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-between mt-4">
                            <button type="button" @click="showForm = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Timeline</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
