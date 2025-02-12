@extends('layouts.app')

@section('content')

    <!-- this is admin dashboard where admin can create and manage events  -->

  <p class="text-center text-gray-400 text-xs">
        Welcome to the <span class="font-semibold">{{ auth()->user()->userrole }} Dashboard!</span>.
  </p>

  <div class="mt-10" x-data="{ showForm: false, showEditForm: false, editData: {} }">
    <!-- Create Event Card -->
    <a @click="showForm = true" class="bg-white shadow-md rounded-lg p-4 w-60 h-60 flex flex-col items-center justify-center border-2 border-dashed border-gray-400 cursor-pointer hover:bg-gray-100 transition">
        <div class="text-5xl text-gray-600">+</div>
        <p class="mt-4 text-gray-600 text-center">Create Event</p>
    </a>

    <!-- Floating Create Form Modal -->
    <div x-show="showForm" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Create Event</h2>
            
            <form method="POST" action="{{ route('admin.events.store') }}"enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Event Name</label>
                    <input type="text" name="event_name" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Upload Sample Paper</label>
                    <input type="file" name="sample_paper" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                </div>
                <div class="flex justify-between">
                    <button type="button" @click="showForm = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Event Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10 px-4 md:px-0">
        @foreach ($events as $event)
          <div class="relative bg-white shadow-md rounded-lg p-4 flex flex-col items-center justify-center border-2 border-gray-400 cursor-pointer hover:bg-gray-100 transition">
            <div class="absolute top-3 right-3 flex space-x-3">
                <!-- Edit Icon -->
                <button @click="showEditForm = true; editData = { id: {{ $event->id }}, event_name: '{{ $event->event_name }}', description: '{{ $event->description }}', start_date: '{{ $event->start_date }}', end_date: '{{ $event->end_date }}' }" class="text-blue-500 hover:text-blue-700">
                    <i class="fas fa-pen"></i>
                </button>
                <!-- Delete Icon -->
                <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" onsubmit="return confirm('Are you sure you want to delete this event?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">{{ $event->event_name }}</h3>
            <p class="mt-2 text-gray-600 text-center">{{ Str::limit($event->description, 100) }}</p>
            <p class="mt-4 text-gray-500 text-sm">Start: {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
            <p class="text-gray-500 text-sm">End: {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}</p>
            <a href="{{ route('events.show', $event->id) }}" class="mt-4 text-blue-500">View Event</a>
          </div>
        @endforeach
    </div>

    <!-- Floating Edit Form Modal -->
    <div x-show="showEditForm" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Edit Event</h2>
            <form method="POST" :action="'/admin/events/' + editData.id" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Event Name</label>
                    <input type="text" name="event_name" x-model="editData.event_name" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" x-model="editData.description" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" x-model="editData.start_date" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" x-model="editData.end_date" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Upload New Sample Paper</label>
                    <input type="file" name="sample_paper" class="mt-1 p-2 w-full border rounded-lg">
                    
                </div>
                <div class="flex justify-between">
                    <button type="button" @click="showEditForm = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
