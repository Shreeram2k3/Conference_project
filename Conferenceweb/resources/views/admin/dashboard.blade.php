@extends('layouts.app')

@section('content')

  <p class="text-center text-gray-400 text-xs">
        Welcome to the <span class="font-semibold">{{ auth()->user()->userrole }} Dashboard!</span>.
  </p>

  <div class="mt-10" x-data="{ showForm: false }">
    <!-- Create Event Card -->
    <a @click="showForm = true" class="bg-white shadow-md rounded-lg p-4 w-60 h-60 flex flex-col items-center justify-center border-2 border-dashed border-gray-400 cursor-pointer hover:bg-gray-100 transition">
        <div class="text-5xl text-gray-600">+</div>
        <p class="mt-4 text-gray-600 text-center">Create Event</p>
    </a>

    <!-- Floating Form Modal -->
    <div x-show="showForm" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Create Event</h2>
            
            <form method="POST" action="{{ route('admin.events.store') }}">
                @csrf

                <!-- Event ID (Hidden) -->
                <input type="hidden" name="events_id" value="{{ old('events_id') }}">

                <!-- Event Name -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Event Name</label>
                    <input type="text" name="event_name" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required></textarea>
                </div>

                <!-- Start Date -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>

                <!-- End Date -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between">
                    <button type="button" @click="showForm = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
    @foreach ($events as $event)
    
      <div class="bg-white shadow-md rounded-lg p-4 flex flex-col items-center justify-center border-2  border-gray-400 cursor-pointer hover:bg-gray-100 transition">
        <h3 class="text-xl font-semibold text-gray-800">{{ $event->event_name }}</h3>
        <p class="mt-2 text-gray-600 text-center">{{ Str::limit($event->description, 100) }}</p>
        <p class="mt-4 text-gray-500 text-sm">Start: {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
        <p class="text-gray-500 text-sm">End: {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}</p>
        <a href="{{ route('events.show', $event->id) }}" class="mt-4 text-blue-500">View Event</a>
      </div>
    @endforeach
</div>


@endsection