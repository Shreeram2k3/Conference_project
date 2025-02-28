@extends('layouts.app')

@section('content')

    <!-- this is admin dashboard where admin can create and manage events  -->

  <p class="text-center text-gray-400 text-xs">
        Welcome to the <span class="font-semibold">{{ auth()->user()->userrole }} Dashboard!</span>.
  </p>

  <div class="mt-10" x-data="{ showForm: false, showEditForm: false, editData: {} }">
    <!-- Create Event Card -->
    <section class="relative mt-10 w-full  p-10 ">
    <div @click="showForm = !showForm" 
         class="fixed mt-10 top-[calc(4rem+1rem)] right-8 bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 text-white rounded-full w-16 h-16 flex items-center justify-center text-3xl shadow-2xl cursor-pointer transition-all duration-300 hover:scale-105 hover:shadow-lg transform ease-in-out" 
         :class="{'rotate-45': showForm}" 
         title="Create Event">
        <span>+</span>
    </div>
</section>




     

    <!-- Floating Create Form Modal -->
    <div x-show="showForm" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Create Event</h2>
            
            <form method="POST" action="{{ route('admin.events.store') }}"enctype="multipart/form-data">
                @csrf
                <!-- eventname  -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Event Name</label>
                    <input type="text" name="event_name" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>

                <!-- description  -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required></textarea>
                </div>

                   <!-- category  -->
                   <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" required class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="National">National</option>
                    <option value="International">International</option>
                   </select>
                </div>

                    <!-- event image -->
                     <div class="mb-4">
                         <label class="block text-sm font-medium text-gray-700">Upload Event Image</label>
                         <input type="file" name="image" class="mt-1 p-2 w-full border rounded-lg">
                     </div>

                     <!-- startdate  -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>

                <!-- enddate  -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                </div>

                <!-- samplepaper  -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Upload Sample Paper</label>
                    <input type="file" name="sample_paper" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                </div>

                <!-- cancel save btns  -->
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

    <!-- Event Card -->
    <div class="relative group rounded-xl overflow-hidden shadow-lg cursor-pointer transition-transform hover:scale-105">
    <a href="{{ route('events.show', $event->id) }}">
        <!-- Event Content -->
        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->event_name }}" 
            class="w-full h-60 object-cover transition-transform group-hover:scale-105 duration-300">
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
        <span class="absolute top-4 left-0 px-5 py-2 text-xs font-semibold text-black
            {{ $event->category == 'International' ? 'bg-lime-500' : 'bg-orange-500' }} rounded-tr-lg rounded-br-lg">
            {{ $event->category }}
        </span>
        <div class="absolute bottom-4 left-4 text-white">
            <h3 class="text-lg font-bold">{{ $event->event_name }}</h3>
            <p class="text-sm">
                {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }} - 
                {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
            </p>
        </div>
    </a>

    <!-- Edit & Delete Buttons (Outside <a>) -->
    <div class="absolute top-3 right-3 flex space-x-3">
        <button @click="showEditForm = true; editData = { id: {{ $event->id }}, event_name: '{{ $event->event_name }}', description: '{{ $event->description }}', start_date: '{{ $event->start_date }}', end_date: '{{ $event->end_date }}' }" class="text-white hover:text-gray-300">
            <i class="fas fa-pen text-blue-500"></i>
        </button>
        <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
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
                
                    <!-- category  -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" required class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="National">National</option>
                    <option value="International">International</option>
                   </select>
                </div>

                    <!-- event image -->
                     <div class="mb-4">
                         <label class="block text-sm font-medium text-gray-700">Upload Event Image</label>
                         <input type="file" name="image" class="mt-1 p-2 w-full border rounded-lg">
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
