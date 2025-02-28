<!DOCTYPE html>
<html lang="en" x-data="{ open: false }">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <title>Conference Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body, html {
        overflow-x: hidden;
        height: 100vh;
    }
    .layout {
        display: flex;
        height: calc(100vh - 4rem);
        overflow: hidden;
    }
    .sidebar {
        width: 16rem;
        flex-shrink: 0;
        background-color: #1f2937;
    }
    .content {
        flex-grow: 1;
        overflow-y: auto;
        height: 100%;
        padding-bottom: 4rem; /* Ensures content is not hidden behind the footer */
    }
    .footer {
        height: 4rem;
        flex-shrink: 0;
    }
  </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gray-700 p-5 w-full fixed top-0 z-50 px-6">
        
        <div class="container mx-4 flex justify-between items-center">
            
            <a href="/dashboard" class="flex items-center space-x-2 text-white text-xl font-bold">
            <img src="{{ asset('images/ddtlogo.png') }}" alt="Logo" class="h-8 w-8">

            <span>Conference</span>
            </a>



            <button @click="open = !open" class="text-white md:hidden focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            
            <div x-data="{ open: false }" class="relative hidden sm:flex justify-end w-full ">

           <!-- Profile Section -->
@if(auth()->check())
    <div @click="open = !open" class="flex items-center space-x-2 cursor-pointer">
        <!-- Profile Icon with First Letter -->
        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold bg-teal-400">
            {{ strtoupper(substr(optional(auth()->user())->name ?? 'U', 0, 1)) }}
        </div>

        <!-- Username & Dropdown Icon -->
        <div class="flex items-center space-x-1">
            <span class="text-white font-thin">{{ optional(auth()->user())->name ?? 'User' }}</span>
            <!-- Dropdown Icon -->
            <svg class="w-4 h-4 text-white transition-transform duration-200" :class="open ? 'rotate-180' : 'rotate-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>
@else
    <a href="{{ route('login') }}" class="text-white">Login</a>
@endif



            <!-- Profile Dropdown (Desktop) -->
            <div x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 top-full mt-5 w-48 shadow-lg bg-gray-900 text-white shadow-lg rounded-lg py-2 z-50 border border-gray-700">
                
                <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-800 transition">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A2 2 0 016 18h12a2 2 0 011.879 1.196M9 15a3 3 0 116 0m-6 0a3 3 0 016 0M16 3.13a4 4 0 00-8 0M12 9v2m0 4h.01"></path>
                    </svg>
                    <span class="text-sm">Profile</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-800 transition">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h4m-2-2v4m0 14v-4m-7-7h4m0 0v4m0-4h4m0 0v4m0-4h4m0 0v4m0-4h4m0 0v4"></path>
                    </svg>
                    <span class="text-sm">Profile Settings</span>
                </a>
                <div class="border-t border-gray-700 my-2"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full text-left px-4 py-3 hover:bg-gray-800 transition">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m-4 4h4m-4-4h4"></path>
                        </svg>
                        <span class="text-sm">Logout</span>
                    </button>
                </form>
            </div>
        </div>


            
        </div>
    </nav>

    <div class="layout pt-16">
        <!-- Sidebar -->
        <aside :class="open ? 'translate-x-0' : '-translate-x-full'" 
       class="sidebar text-white fixed inset-y-0 left-0 transform transition-transform duration-300 md:translate-x-0 md:relative md:w-64 z-40 py-6 px-4 mt-16 md:mt-0">
    <ul>
        <li class="mb-2">
            <a href="/dashboard" 
               class="block px-4 py-2 rounded-lg transition-all duration-300 border-l-4 
                      {{ Request::is('dashboard') ? 'border-blue-500 pl-6 bg-gray-700 font-medium' : 'border-transparent hover:border-blue-500 hover:pl-6' }}">
                Home
            </a>
        </li>
        <li class="mb-2">
            <a href="/events" 
               class="block px-4 py-2 rounded-lg transition-all duration-300 border-l-4 
                      {{ Request::is('events') ? 'border-blue-500 pl-6 bg-gray-700 font-medium' : 'border-transparent hover:border-blue-500 hover:pl-6' }}">
                Events
            </a>
        </li>
        @if(auth()->check() && auth()->user()->userrole === 'admin')
    <li class="mb-2">
        <a href="/admin/dashboard" 
           class="block px-4 py-2 rounded-lg transition-all duration-300 border-l-4 
                  {{ Request::is('admin/dashboard') ? 'border-blue-500 pl-6 bg-gray-700 font-medium' : 'border-transparent hover:border-blue-500 hover:pl-6' }}">
            Admin Dashboard
        </a>
    </li>
    <li class="mb-2">
        <a href="{{ route('admin.registration') }}" 
           class="block px-4 py-2 rounded-lg transition-all duration-300 border-l-4 
                  {{ Request::is('admin/registration') ? 'border-blue-500 pl-6 bg-gray-700 font-medium' : 'border-transparent hover:border-blue-500 hover:pl-6' }}">
            Registrations
        </a>
    </li>
@elseif(auth()->check() && auth()->user()->userrole === 'organizer')
    <li class="mb-2">
        <a href="/organizer/dashboard" 
           class="block px-4 py-2 rounded-lg transition-all duration-300 border-l-4 
                  {{ Request::is('organizer/dashboard') ? 'border-blue-500 pl-6 bg-gray-700 font-medium' : 'border-transparent hover:border-blue-500 hover:pl-6' }}">
            Organizer Dashboard
        </a>
    </li>
@endif

    </ul>
    

    <div x-data="{ open: false }" class="sm:hidden fixed bottom-4 left-4 w-full">

    <!-- Profile Section (Click to Toggle) -->
    <div @click="open = !open" class="flex items-center space-x-2 cursor-pointer">
        <!-- Profile Icon -->
        @if(auth()->check())
    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold bg-teal-400">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>

    <!-- Username & Dropdown Icon -->
    <div class="flex items-center space-x-1">
        <span class="text-white font-thin">{{ auth()->user()->name }}</span>
        <svg class="w-4 h-4 text-white transition-transform duration-300 ease-in-out"
             :class="open ? 'rotate-180' : 'rotate-0'" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>
@else
    <a href="{{ route('login') }}" class="text-white">Login</a>
@endif

    </div>

    <!-- Drop-Up Menu -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200 transform"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute bottom-full mb-4 left-0 w-50 bg-gray-900 text-white shadow-lg rounded-lg py-2 z-50 border border-gray-700">
        
        <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-800 transition">
            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A2 2 0 016 18h12a2 2 0 011.879 1.196M9 15a3 3 0 116 0m-6 0a3 3 0 016 0M16 3.13a4 4 0 00-8 0M12 9v2m0 4h.01"></path>
            </svg>
            <span class="text-sm">Profile</span>
        </a>
        <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-800 transition">
            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h4m-2-2v4m0 14v-4m-7-7h4m0 0v4m0-4h4m0 0v4m0-4h4m0 0v4m0-4h4m0 0v4m0-4h4m0 0v4"></path>
            </svg>
            <span class="text-sm">Profile Settings</span>
        </a>
        <div class="border-t border-gray-700 my-2"></div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center w-full text-left px-4 py-3 hover:bg-gray-800 transition">
                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m-4 4h4m-4-4h4"></path>
                </svg>
                <span class="text-sm">Logout</span>
            </button>
        </form>
    </div>
</div>



</aside>




        <!-- Main Content -->
        <main class="content p-6 bg-white ">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-700 text-white text-center py-4 footer">
        <p>&copy; 2025 Conference Management System</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".active-link").forEach(link => {
                let linkPath = new URL(link.href, window.location.origin).pathname;
                let currentPath = window.location.pathname;
                if (currentPath === linkPath || currentPath.startsWith(linkPath + '/')) {
                    link.classList.add("bg-gray-700");
                }
            });
        });

        
        document.addEventListener("DOMContentLoaded", function () {
            const profileIcon = document.getElementById("profileIcon");
            const profileMenu = document.getElementById("profileMenu");

            if (profileIcon && profileMenu) {
                profileIcon.addEventListener("click", function () {
                    profileMenu.classList.toggle("hidden");
                });

                document.addEventListener("click", function (event) {
                    if (!profileIcon.contains(event.target) && !profileMenu.contains(event.target)) {
                        profileMenu.classList.add("hidden");
                    }
                });
            }
        });


    </script>
</body>
</html>
