@extends('layouts.app')

@section('content')

<!-- This is the admin's Add timelines to event page  -->

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8"> 
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

    <!-- Download Paper Format Button -->
    @if ($event->sample_paper)

        <div class="mt-10">
            <h2 class="text-2xl font-semibold">Paper Format</h2>

        </div>
        <div class="mt-6 ">
            
        <!-- <button onclick="document.getElementById('previewFrame').src='{{ Storage::url($event->sample_paper) }}'; document.getElementById('previewContainer').classList.remove('hidden');" 
                    class="inline-block px-6 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">
                Preview
            </button>

            <button onclick="document.getElementById('previewFrame').src='https://docs.google.com/gview?url={{ Storage::url($event->sample_paper) }}&embedded=true'; document.getElementById('previewContainer').classList.remove('hidden');" 
        class="inline-block px-6 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">
        Preview
    </button> -->

            
            <a href="{{Storage::url($event->sample_paper) }}" target="_blank"
            class="inline-block px-6 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">
            
                📄 Download
            </a>

            <div id="previewContainer" class="mt-6 hidden">
            <iframe id="previewFrame" class="w-full h-[500px] border border-gray-300 rounded-lg"></iframe>
            </div>
        </div>
    @endif

    <!-- Timeline Section -->
    <div class="mt-10" x-data="{ showEditForm: false, editData: { id: '', title: '', description: '', date: '' } }" >
        <h2 class="text-2xl font-semibold">Event Timeline</h2>

        <!-- Display existing timelines -->
        <div class="mt-6 space-y-4">
            @forelse ($timelines as $timeline)
                <div class="bg-white shadow-md rounded-lg p-4 border border-gray-300 relative">
                    <!-- Icons Positioned at the Top-Right Corner -->
                    <div class="absolute top-3 right-3 flex space-x-3">
                        <!-- Edit Icon -->
                        <button @click="showEditForm = true; editData = { 
                            id: {{ $timeline->id }}, 
                            title: '{{ $timeline->title }}', 
                            description: '{{ $timeline->description }}', 
                            date: '{{ $timeline->date }}' 
                        }" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-pen"></i>
                        </button>

                        <!-- Delete Icon -->
                        <form method="POST" action="{{ route('timelines.destroy', $timeline->id) }}" onsubmit="return confirm('Are you sure you want to delete this timeline?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>

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
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>{{ old('description') }}</textarea>
                        </div>

                            <!-- Event Date Constraints -->
                        <p class="text-sm text-gray-500">Event Start: {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-500 mb-4">Event End: {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}</p>

                        
                        <!-- Timeline Date -->
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Timeline Date</label>
                            <input type="date" name="date" id="date" value="{{ old('date') }}" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                        </div>

                            @error('date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                        <!-- Buttons -->
                        <div class="flex justify-between mt-4">
                            <button type="button" @click="showForm = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Timeline</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div x-show="showEditForm" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-4">Edit Timeline</h2>

                <form method="POST" :action="'/timelines/' + editData.id">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" x-model="editData.title" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" x-model="editData.description" rows="3" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required></textarea>
                    </div>

                         <!-- Event Date Constraints -->
                         <p class="text-sm text-gray-500">Event Start: {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
                         <p class="text-sm text-gray-500 mb-4">Event End: {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}</p>


                    <!-- Date -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" x-model="editData.date" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                    </div>
                            @error('date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                    <div class="flex justify-between mt-4">
                        <button type="button" @click="showEditForm = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div x-data="{ showEditForm: false, editData: {} }">

<div class=" mt-16 container mx-auto p-6 bg-white shadow-lg rounded-lg">
    <!-- Title & Search Section -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
        <h2 class="text-xl sm:text-2xl font-semibold">
            <i class="fas fa-clipboard-list text-blue-500"></i> Registrations for "{{ $event->event_name }}"
        </h2>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.registration') }}" class="flex flex-col sm:flex-row gap-2">
            <!-- Keep event_id as hidden since the route handles event-specific data -->
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            <!-- Search Input -->
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by name, email, phone..." 
                    class="w-full sm:w-72 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                >
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>

            <!-- Search Button -->
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition flex items-center">
                <i class="fas fa-search mr-2"></i> Search
            </button>
        </form>
    </div>

    <!-- Responsive Table -->
    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full bg-white border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200 text-gray-700 uppercase text-left">
                <tr>
                    <th class="py-3 px-4 border">Sno</th>
                    <th class="py-3 px-4 border"><i class="fas fa-user"></i> Name</th>
                    <th class="py-3 px-4 border"><i class="fas fa-envelope"></i> Email</th>
                    <th class="py-3 px-4 border"><i class="fas fa-phone"></i> Phone</th>
                    <th class="py-3 px-4 border"><i class="fas fa-university"></i> Institution</th>
                    <th class="py-3 px-4 border"><i class="fas fa-briefcase"></i> Designation</th>
                    <th class="py-3 px-4 border"><i class="fas fa-clock"></i> Registered At</th>
                    <th class="py-3 px-4 border"> Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($registrations as $index => $registration)
                    <tr class="border hover:bg-gray-100 transition">
                        <td class="py-3 px-4 border">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 border">{{ $registration->name }}</td>
                        <td class="py-3 px-4 border">{{ $registration->email }}</td>
                        <td class="py-3 px-4 border">{{ $registration->phone ?? 'N/A' }}</td>
                        <td class="py-3 px-4 border">{{ $registration->institution ?? 'N/A' }}</td>
                        <td class="py-3 px-4 border">{{ $registration->designation ?? 'N/A' }}</td>
                        <td class="py-3 px-4 border">{{ $registration->created_at->format('d M Y, H:i A') }}</td>

                        <td class="py-3 px-4 border text-center">
                        <div class="flex justify-center rounded-lg overflow-hidden">
                            <!-- Send Email Icon  -->
                            <a href="#" class="p-2 bg-cyan-500 text-white hover:bg-cyan-600 transition rounded-l-lg">
                                <i class="fas fa-paper-plane"></i>
                            </a>

                            <!-- Generate Certificate Icon  -->
                            <a href="#" class="p-2 bg-green-700 text-white hover:bg-green-800 transition">
                                <i class="fas fa-graduation-cap"></i>
                            </a>
                           <!-- Edit icon -->
                           <button 
                                @click="showEditForm = true; editData = { 
                                    id: {{ $registration->id }}, 
                                    name: '{{ $registration->name }}', 
                                    email: '{{ $registration->email }}', 
                                    phone: '{{ $registration->phone }}', 
                                    institution: '{{ $registration->institution }}', 
                                    designation: '{{ $registration->designation }}', 
                                    event_id: '{{ $registration->event_id }}' 
                                }" 
                                class="p-2 bg-stone-700 text-white hover:bg-stone-900 transition">
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            <!-- Delete Icon -->
                            <form method="POST" action="{{ route('admin.registrations.destroy', $registration->id) }}" onsubmit="return confirm ('Are you sure you want to delete this registration?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-500 text-white hover:bg-red-600 transition rounded-r-lg">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">No registrations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
 

    <!-- Floating Edit Form Modal -->
    <div x-show="showEditForm" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Edit Registration</h2>
            <form method="POST" :action="'/admin/registrations/' + editData.id">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" x-model="editData.name" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" x-model="editData.email" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" x-model="editData.phone" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Institution</label>
                    <input type="text" name="institution" x-model="editData.institution" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Designation</label>
                    <input type="text" name="designation" x-model="editData.designation" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Event</label>
                    <select name="event_id" x-model="editData.event_id" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                        @endforeach
                    </select>
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
