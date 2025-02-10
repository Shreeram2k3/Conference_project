@extends('layouts.app')

@section('content')
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

            <!-- Search Button -->
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition flex items-center">
                <i class="fas fa-search mr-2"></i> Search
            </button>
        </form>
    </div>

    <!-- Responsive Table Wrapper -->
    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full bg-white border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200 text-gray-700 uppercase text-left sticky top-0">
                <tr>
                    <th class="py-3 px-4 border">S.no</th>
                    <th class="py-3 px-4 border"><i class="fas fa-user"></i> Name</th>
                    <th class="py-3 px-4 border"><i class="fas fa-envelope"></i> Email</th>
                    <th class="py-3 px-4 border"><i class="fas fa-phone"></i> Phone</th>
                    <th class="py-3 px-4 border"><i class="fas fa-university"></i> Institution</th>
                    <th class="py-3 px-4 border"><i class="fas fa-briefcase"></i> Designation</th>
                    <th class="py-3 px-4 border"><i class="fas fa-calendar-alt"></i> Event</th>
                    <th class="py-3 px-4 border"><i class="fas fa-clock"></i> Registered At</th>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
