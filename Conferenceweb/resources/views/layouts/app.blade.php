<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Conference Management</title>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-5 relative">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/dashboard" class="text-white text-xl font-bold">Conference</a>
            
            <!-- Mobile Menu Button -->
            <button id="menu-btn" class="text-white md:hidden focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Navigation Links (Desktop) -->
            <ul class="hidden md:flex items-center space-x-6 text-white">
                <li><a href="/dashboard" class="hover:text-gray-300">Home</a></li>
                <li><a href="/events" class="hover:text-gray-300">Events</a></li>
                
                <!-- Role-Based Links -->
                @if(auth()->user()->userrole === 'admin')
                    <li><a href="/admin/dashboard" class="hover:text-gray-300">Admin Dashboard</a></li>
                @elseif(auth()->user()->userrole === 'organizer')
                    <li><a href="/organizer/dashboard" class="hover:text-gray-300">Organizer Dashboard</a></li>
                @endif
                
                <!-- Logout Button -->
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="hover:text-gray-300">Logout</button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Mobile Dropdown Menu -->
        <ul id="mobile-menu" class="absolute top-full left-0 w-full bg-blue-500 text-white flex flex-col space-y-2 p-4 md:hidden 
            transform scale-y-0 origin-top transition-transform duration-300 ease-in-out"> 
            <li><a href="/dashboard" class="hover:text-gray-300">Home</a></li>
            <li><a href="/events" class="hover:text-gray-300">Events</a></li>
            
            <!-- Role-Based Links -->
            @if(auth()->user()->userrole === 'admin')
                <li><a href="/admin/dashboard" class="hover:text-gray-300">Admin Dashboard</a></li>
            @elseif(auth()->user()->userrole === 'organizer')
                <li><a href="/organizer/dashboard" class="hover:text-gray-300">Organizer Dashboard</a></li>
            @endif
            
            <!-- Mobile Logout Button -->
            <li>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="hover:text-gray-300">Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto py-6">
        @yield('content') <!-- This is where the specific content from views will go -->
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
        <p>&copy; 2025 Conference Management System</p>
    </footer>

    <script>
        const menuBtn = document.getElementById("menu-btn");
        const mobileMenu = document.getElementById("mobile-menu");

        menuBtn.addEventListener("click", () => {
            if (mobileMenu.classList.contains("scale-y-0")) {
                mobileMenu.classList.remove("scale-y-0");
                mobileMenu.classList.add("scale-y-100");
            } else {
                mobileMenu.classList.remove("scale-y-100");
                mobileMenu.classList.add("scale-y-0");
            }
        });
    </script>

    <!-- Add TailwindJS or other scripts here -->
    @vite('resources/js/app.js')

</body>
</html>
