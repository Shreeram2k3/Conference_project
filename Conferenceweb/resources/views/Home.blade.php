<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white overflow-y-scroll h-screen snap-mandatory snap-y" onload="startCarousel();" onscroll="handleScroll();">

    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-transparent text-white py-4">
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
            <ul id="menu" class="hidden lg:flex lg:space-x-6">
                <li><a href="/" class="nav-link pb-1 border-b-2 border-transparent hover:border-blue-400 transition font-medium">Home</a></li>
                <li><a href="#Aboutus" class="nav-link pb-1 border-b-2 border-transparent hover:border-blue-400 transition font-medium">About us</a></li>
                <li><a href="#explore" class="nav-link pb-1 border-b-2 border-transparent hover:border-blue-400 transition font-medium">Explore</a></li>
                <li><a href="#contact" class="nav-link pb-1 border-b-2 border-transparent hover:border-blue-400 transition font-medium">Contact</a></li>
            </ul>
        </div>
        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu" class="lg:hidden hidden mt-4">
            <ul class="bg-gray-800 rounded-lg py-2">
                <li><a href="/" class="block px-4 py-2 text-white hover:bg-gray-700">Home</a></li>
                <li><a href="#Aboutus" class="block px-4 py-2 text-white hover:bg-gray-700">About us</a></li>
                <li><a href="#explore" class="block px-4 py-2 text-white hover:bg-gray-700">Explore</a></li>
                <li><a href="#contact" class="block px-4 py-2 text-white hover:bg-gray-700">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="carousel" class="relative w-full h-screen overflow-hidden snap-start">
        <div id="carousel-container" class="relative w-full h-full">
            <!-- Slide 1 -->
            <div class="slide absolute inset-0 flex items-center justify-center text-center bg-cover bg-center opacity-0 transition-opacity duration-1000" 
                style="background-image: url('/images/mapimg.jpg');">
                <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                <div class="relative z-10 text-white px-4">
                    <h1 class="text-4xl md:text-5xl font-extrabold">Conferences <span class="text-blue-300">For You</span></h1>
                    <p class="mt-4 text-lg">Join the best conferences around the world</p>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="slide absolute inset-0 flex items-center justify-center text-center bg-cover bg-center opacity-0 transition-opacity duration-1000" 
                style="background-image: url('/images/heroslide2.jpg');">
                <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                <div class="relative z-10 text-white px-4">
                    <h1 class="text-4xl md:text-5xl font-extrabold">Expand Your <span class="text-blue-300">Knowledge</span></h1>
                    <p class="mt-4 text-lg">Connect with experts and enthusiasts</p>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="slide absolute inset-0 flex items-center justify-center text-center bg-cover bg-center opacity-0 transition-opacity duration-1000" 
                style="background-image: url('/images/heroslide3.avif');">
                <div class="absolute inset-0 bg-black bg-opacity-70"></div>
                <div class="relative z-10 text-white px-4">
                    <h1 class="text-4xl md:text-5xl font-extrabold">Join the <span class="text-blue-300">Future</span></h1>
                    <p class="mt-4 text-lg">Discover innovations and insights</p>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <button class="absolute left-5 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-60 hover:bg-opacity-80 p-4 rounded-full transition duration-300" onclick="prevSlide()">❮</button>
        <button class="absolute right-5 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-60 hover:bg-opacity-80 p-4 rounded-full transition duration-300" onclick="nextSlide()">❯</button>
    </section>

    <!-- Aboutus section -->
    <section id="Aboutus" class="h-screen flex items-center justify-center bg-white text-black snap-start">
        <h1 class="text-4xl">About us</h1>
    </section>

    <!-- Explore Section -->
    <section id="explore" class="m-h-screen snap-start bg-black text-white py-16 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white drop-shadow-lg">
                Explore <span class="text-indigo-400">Conferences</span>
            </h1>
            <p class="mt-4 text-lg text-gray-300">
                Discover world-class conferences and events tailored for you.
            </p>
        </div>

        <!-- Event Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
            @foreach ($events as $event)
            <a href="{{ route('eventinfo', $event->id) }}" class="group block relative transform transition duration-500 hover:scale-[1.02]">
                <div class="relative rounded-2xl overflow-hidden shadow-xl bg-gray-800/60 backdrop-blur-lg border border-gray-700">
                    <!-- Background Image with Overlay -->
                    <div class="relative">
                        <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('images/default-event.jpg') }}" 
                            alt="{{ $event->event_name }}" 
                            class="w-full h-72 object-cover transition-all duration-500 group-hover:scale-105 opacity-90">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                    </div>

                    <!-- Event Info -->
                    <div class="absolute bottom-6 left-6 right-6">
                        <h3 class="text-2xl font-bold text-white drop-shadow-md">{{ $event->event_name }}</h3>
                        <p class="text-md text-gray-300">
                            {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }} - 
                            {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                        </p>
                    </div>

                    <!-- Category Badge -->
                    <span class="absolute top-4 left-0 px-5 py-2 text-sm font-light text-white uppercase tracking-wide
                       {{ $event->category == 'International' ? 'bg-lime-500' : ($event->category == 'National' ? 'bg-orange-500' : 'bg-blue-500') }}
                    rounded-tr-lg rounded-br-lg
                        rounded-tr-xl rounded-br-xl shadow-md">
                        {{ $event->category }}
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    <!-- Contactus section -->
    <section id="contact" class="h-screen flex items-center justify-center bg-white text-black snap-start">
        <h1 class="text-4xl">Contact Us</h1>
    </section>

    <!-- JavaScript -->
    <script>
        function handleScroll() {
            const navbar = document.getElementById("navbar");
            const links = document.querySelectorAll(".nav-link");
            const sections = document.querySelectorAll("section");

            // Change navbar background on scroll
            if (window.scrollY > 100) {
                navbar.classList.remove("bg-transparent", "text-white");
                navbar.classList.add("bg-stone-200", "text-stone-700");
            } else {
                navbar.classList.remove("bg-stone-200", "text-stone-700");
                navbar.classList.add("bg-transparent", "text-white");
            }

            // Highlight active section in navbar
            let currentSection = "";
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.scrollY >= sectionTop - 50 && window.scrollY < sectionTop + sectionHeight - 50) {
                    currentSection = section.getAttribute("id");
                }
            });

            links.forEach(link => {
                link.classList.remove("border-blue-400");
                if (link.getAttribute("href").substring(1) === currentSection) {
                    link.classList.add("border-blue-400"); // Active section gets the blue underline
                }
            });
        }

        window.addEventListener("scroll", handleScroll);

        let currentIndex = 0;
        const slides = document.querySelectorAll(".slide");
        const totalSlides = slides.length;

        function updateCarousel() {
            slides.forEach((slide, index) => {
                slide.classList.remove("opacity-100"); // Hide all slides
                slide.classList.add("opacity-0");
            });

            slides[currentIndex].classList.add("opacity-100"); // Show active slide
            slides[currentIndex].classList.remove("opacity-0");
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateCarousel();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }

        function startCarousel() {
            slides[0].classList.add("opacity-100"); // Start with the first slide visible
            setInterval(nextSlide, 5000); // Auto-change every 5 seconds
        }

        // Enable keyboard navigation
        document.addEventListener("keydown", function(event) {
            if (event.key === "ArrowRight") {
                nextSlide();
            } else if (event.key === "ArrowLeft") {
                prevSlide();
            }
        });

        // Toggle mobile menu
        document.getElementById("menu-toggle").addEventListener("click", function() {
            const mobileMenu = document.getElementById("mobile-menu");
            mobileMenu.classList.toggle("hidden");
        });
    </script>

</body>
</html>