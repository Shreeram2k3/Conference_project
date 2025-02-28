<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event info</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</head>
<body class="bg-white text-white" x-data="{ showForm: false, eventId: '{{ $event->id }}', eventName: '{{ $event->event_name }}' }">

  


    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-stone-100 text-stone-700 py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center space-x-2 text-xl font-bold">
                <img src="{{ asset('images/ddtlogo.png') }}" alt="Logo" class="h-8 w-8">
                <span id="logo-text">Conference</span>
            </a>
            <button id="menu-toggle" class="lg:hidden focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </nav>

   
    <!-- Event Banner -->
    <div class="relative w-full h-[50vh] overflow-hidden shadow-lg">
    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('images/default-event.jpg') }}" 
         alt="{{ $event->event_name }}" 
         class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

    <!-- Event Title -->
    <div class="absolute bottom-6 left-6 text-white">
        <h1 class="text-4xl font-bold">{{ $event->event_name }}</h1>
        <p class="text-lg opacity-90">
            {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }} - 
            {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
        </p>
    </div>
</div>


    
    <!-- Event Details -->
<div class="container mx-auto px-6 mt-24">

    <div class="bg-gray-800 text-white mt-10 p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-4">Event Details</h2>
        <p class="text-gray-300">{{ $event->description }}</p>
        
        <div class="mt-4">
            <span class="inline-block px-4 py-2 text-sm font-semibold uppercase rounded-lg
                 {{ $event->category == 'International' ? 'bg-lime-500' : ($event->category == 'National' ? 'bg-orange-500' : 'bg-blue-500') }}">
                {{ $event->category }}
            </span>
        </div>

         
      <!-- Event Timeline -->
      <div class="bg-gray-800 text-white mt-10 p-6 rounded-lg shadow-lg">
          <h2 class="text-3xl font-bold text-center mb-8 text-lime-400">Event Flow</h2>

          <div class="relative mx-auto w-full lg:w-2/3">
              <!-- Left Vertical Line -->
              <div class="absolute left-3 h-full w-1 bg-gray-600"></div>

              @foreach ($event->timelines->sortBy('date') as $timeline)
                  <div class="relative flex items-start space-x-6 mb-8">
                      <!-- Timeline Indicator -->
                      <div class="w-5 h-5 bg-lime-500 rounded-full border-4 border-gray-900 shadow-lg"></div>

                      <!-- Timeline Content -->
                      <div class="bg-gray-700 p-5 rounded-lg shadow-lg w-full">
                          <p class="text-xl font-semibold text-lime-300">{{ $timeline->title }}</p>
                          <p class="text-sm text-gray-400">
                              {{ \Carbon\Carbon::parse($timeline->date)->format('M d, Y') }}
                          </p>
                          <p class="text-gray-300 mt-2">{{ $timeline->description }}</p>
                      </div>
                  </div>
              @endforeach
          </div>
          
          <!-- Register Button -->
        <div class="mt-6 flex justify-center">
            <a href="#" @click="showForm = true" 
              class="inline-block w-full max-w-md text-center px-6 py-3 border-2 border-red-400 text-red-400 font-semibold rounded-full 
                      hover:bg-red-400 hover:text-white transition duration-300">
                Register Now
            </a>
        </div>

      </div>
    </div>


    <!-- downloadpaper format -->
    <div class="bg-gray-800 text-white mt-10 p-6 rounded-lg shadow-lg">
        
          @if ($event->sample_paper)
          <div class="mt-3">
              <h2 class="text-2xl font-semibold">Paper Format</h2>
          </div>

          <div class="mt-6">
              <a href="{{ Storage::url($event->sample_paper) }}" download 
                class="inline-flex items-center gap-2 px-6 py-2 bg-lime-500 text-white font-medium rounded-lg shadow 
                        hover:bg-lime-600 transition duration-300">
                  📄 Download Paper Format
              </a>
          </div>
      @endif

    </div>

    <!-- Committee members section  -->
    <div class="bg-gray-800 text-white mt-10 p-6 rounded-lg shadow-lg">

    <h2 class="text-2xl font-semibold">Comittee members </h2>
    </div>

     <!-- register modal section  -->

     
          <!-- Registration Modal -->
          <div x-show="showForm" x-transition.opacity class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50" x-cloak>
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative text-gray-800" @click.outside="showForm = false">
        <button @click="showForm = false" class="absolute top-2 right-2 text-gray-600 hover:text-black text-xl">&times;</button>

        <h2 class="text-xl font-semibold mb-4 text-gray-900">Register for <span x-text="eventName"></span></h2>

        <form method="POST" :action="'/events/' + eventId + '/register'" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-800">Name</label>
                <input type="text" name="name" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-800">Email</label>
                <input type="email" name="email" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-800">Phone</label>
                <input type="text" name="phone" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-800">Institution</label>
                <input type="text" name="institution" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <!-- Registration Mode -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-800">Mode</label>
                <select name="mode" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                    <option value="Online">Online</option>
                    <option value="Offline">Offline</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-800">Designation</label>
                <select name="designation" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
                    <option value="Student(UG)">Student(UG)</option>
                    <option value="Student(PG)">Student(PG)</option>
                    <option value="Research Scholar">Research Scholar</option>
                    <option value="AssProf/Prof">AssProf/Prof</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-800">Upload Abstract</label>
                <input type="file" name="abstract" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <div class="flex justify-between mt-4">
                <button type="button" @click="showForm = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>
    

</body>
</html>
