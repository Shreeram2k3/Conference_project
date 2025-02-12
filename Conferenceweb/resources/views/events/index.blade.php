@extends('layouts.app')

@section('content')

<!-- this is the page where events are listed and the user can register for events here-->
    <p class="text-center text-gray-400 text-xs">
        Explore and register for exciting events!
    </p>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4 text-center">
            {{ session('success') }}
        </div>
    @endif

    <div x-data="{ showForm: false, eventId: null, eventName: '' }">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10 px-4 md:px-10">
            @foreach ($events as $event)
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col items-center justify-center border-2 border-gray-400 cursor-pointer hover:bg-gray-100 transition relative">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $event->event_name }}</h2>
                    <p class="mt-2 text-gray-600 text-center">{{ Str::limit($event->description, 100) }}</p>
                    <p class="mt-4 text-gray-500 text-sm">Start: {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
                    <p class="text-gray-500 text-sm">End: {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}</p>
                    
                    <button @click="showForm = true; eventId = {{ $event->id }}; eventName = '{{ $event->event_name }}'" 
                        class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg w-full text-center hover:bg-blue-700 transition">
                        Register
                    </button>
                </div>
            @endforeach
        </div>

        <div x-show="showForm" x-transition.opacity class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96" @click.away="showForm = false">
                <h2 class="text-xl font-semibold mb-4">Register for <span x-text="eventName"></span></h2>
                <form method="POST" :action="'/events/' + eventId + '/register' "enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Institution</label>
                        <input type="text" name="institution" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Designation</label>
                        <select name="designation" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                            <option value="Student(UG)">Student(UG)</option>
                            <option value="Student(PG)">Student(PG)</option>
                            <option value="Research Scholar">Research Scholar</option>
                            <option value="AssProf/Prof">AssProf/Prof</option>
                        </select>
                    </div>

                    <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Upload Abstract</label>
                    <input type="file" name="abstract" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                    <div class="flex justify-between mt-4">
                        <button type="button" @click="showForm = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
