@extends('layouts.app')

@section('content')

<!-- this is event registration page where admin can see the list of all the registrations for the events -->

<!-- Flash Messages -->
@if (session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
    {{ session('success') }}
</div>
@endif

<div x-data="{ showEditForm: false, editData: {} }">
<div class="container mx-auto p-4 sm:p-6 bg-white shadow-lg rounded-lg">
    <!-- Title & Filter Section -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
        <h2 class="text-xl sm:text-2xl font-semibold mb-2 sm:mb-0">
            <i class="fas fa-clipboard-list text-blue-500"></i> Event Registrations
        </h2>

        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('admin.registration') }}" class="w-full sm:w-auto flex flex-col sm:flex-row gap-2">
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
            <!-- Event Dropdown -->
            <div class="relative">
                <select 
                    name="event_id" 
                    class="w-full sm:w-auto px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                    onchange="this.form.submit()"
                >
                    <option value="">All Events</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->event_name }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-calendar-alt absolute right-3 top-3 text-gray-400"></i>
            </div>

        </form>
    </div>

    <!-- Responsive Table Wrapper -->
    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full bg-white border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200 text-gray-700 uppercase text-left sticky top-0">
                <tr>
                    <th class="py-3 text-center px-4 border">Sno</th>
                    <th class="py-3 text-center px-4 border"><i class="fas fa-user"></i> Name</th>
                    <th class="py-3 text-center px-4 border"><i class="fas fa-envelope"></i> Email</th>
                    <th class="py-3 text-center px-4 border"><i class="fas fa-phone"></i> Phone</th>
                    <th class="py-3 text-center px-4 border"><i class="fas fa-university"></i> Institution</th>
                    <th class="py-3 text-center px-4 border"><i class="fas fa-briefcase"></i> Designation</th>
                    <th class="py-3 text-center px-4 border"><i class="fas fa-calendar-alt"></i> Event</th>
                    <th class="py-3 text-center px-4 border"><i class="fas fa-clock"></i> Registered At</th>
                    <th class="py-3 text-center px-4 border"> Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($registrations as $index => $registration)
                <tr class="border hover:bg-gray-100 transition">
                    <td class="py-3 px-4 border">{{ $index + 1 }}</td>
                    <td class="py-3 px-4 border">{{ $registration->name }}</td>
                    <td class="py-3 px-4 border">{{ $registration->email }}</td>
                    <td class="py-3 px-4 border">{{ $registration->phone ?? 'N/A' }}</td>
                    <td class="py-3 px-4 border">{{ $registration->institution ?? 'N/A' }}</td>
                    <td class="py-3 px-4 border">{{ $registration->designation ?? 'N/A' }}</td>
                    <td class="py-3 px-4 border">{{ $registration->event->event_name }}</td>
                    <td class="py-3 px-4 border">{{ $registration->created_at->format('d M Y, H:i A') }}</td>
                    
                    <td class="py-3 px-4 border text-center">
                        <div class="flex rounded-lg overflow-hidden">
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
                @endforeach
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
