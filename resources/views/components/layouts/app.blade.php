<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>BiharShop.com</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <!-- ====== BiharShop Navbar ====== -->
    <nav class="bg-white/90 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-2xl font-bold text-teal-600">BiharShop</a>

                <!-- Desktop Links -->
                <div class="hidden md:flex items-center space-x-8 font-medium text-gray-700">
                    <a href="{{ route('home') }}" class="hover:text-teal-600 transition">Home</a>
                    <a href="{{ route('shop') }}" class="hover:text-teal-600 transition">Products</a>
                    <a href="#about" class="hover:text-teal-600 transition">About</a>
                    <a href="#contact" class="hover:text-teal-600 transition">Contact</a>
                    @auth
                        @if (auth()->user()->membership)
                            <a href="{{ route('member.dashboard') }}" class="hover:text-teal-600 transition">Member
                                Panel</a>
                        @else
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center px-3 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition">Join
                                Network</a>
                        @endif
                        @if (auth()->guard('admin')->check())
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-teal-600 transition">Admin Panel</a>
                        @endif
                    @endauth
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <!-- Cart -->
                    <a href="{{ route('cart') }}" class="relative p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full px-1.5">0</span>
                    </a>

                    @guest
                        <a href="{{ route('register') }}"
                            class="hidden md:inline-flex items-center px-3 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition">Join
                            Network</a>
                    @endguest
                    @auth
                        @if (!auth()->user()->membership)
                            <a href="{{ route('register') }}"
                                class="hidden md:inline-flex items-center px-3 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition">Join
                                Network</a>
                        @endif
                    @endauth

                    <!-- Auth / User -->
                    @auth
                        <div class="relative">
                            <button id="userBtn" class="flex items-center text-sm font-medium hover:text-teal-600">
                                <span class="mr-1">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div id="userMenu"
                                class="hidden absolute right-0 mt-2 w-52 bg-white/95 backdrop-blur border border-gray-100 rounded-xl shadow-lg overflow-hidden">
                                @if (auth()->user()->membership)
                                    <a href="{{ route('member.dashboard') }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-50">Member Panel</a>
                                @else
                                    <a href="{{ route('register') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Join
                                        Network</a>
                                @endif
                                @if (auth()->guard('admin')->check())
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-50">Admin Panel</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 font-medium transition">Login</a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button id="menuBtn" class="md:hidden p-2 rounded-md hover:bg-gray-100 transition">
                        <svg id="openIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu"
            class="hidden md:hidden bg-white border-t border-gray-100 shadow-xl absolute w-full left-0 top-16 z-[9999]">
            <div class="px-4 py-4 space-y-3 font-medium text-gray-700">
                <a href="{{ route('home') }}" class="block hover:text-teal-600">Home</a>
                <a href="{{ route('shop') }}" class="block hover:text-teal-600">Products</a>
                <a href="#about" class="block hover:text-teal-600">About</a>
                <a href="#contact" class="block hover:text-teal-600">Contact</a>
                @auth
                    @if (auth()->user()->membership)
                        <a href="{{ route('member.dashboard') }}" class="block hover:text-teal-600">Member Panel</a>
                    @else
                        <a href="{{ route('register') }}"
                            class="block bg-teal-600 text-white text-center rounded-md py-2 hover:bg-teal-700">Join
                            Network</a>
                    @endif
                    @if (auth()->guard('admin')->check())
                        <a href="{{ route('admin.dashboard') }}" class="block hover:text-teal-600">Admin Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left hover:text-teal-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('register') }}"
                        class="block bg-teal-600 text-white text-center rounded-md py-2 hover:bg-teal-700">Join Network</a>
                    <a href="{{ route('login') }}"
                        class="block bg-teal-600 text-white text-center rounded-md py-2 hover:bg-teal-700">Login</a>
                @endauth
            </div>
        </div>
    </nav>
    <!-- ====== JS (Simple Logic) ====== -->
    <script>
        const menuBtn = document.getElementById("menuBtn");
        const mobileMenu = document.getElementById("mobileMenu");
        const openIcon = document.getElementById("openIcon");
        const closeIcon = document.getElementById("closeIcon");
        const userBtn = document.getElementById("userBtn");
        const userMenu = document.getElementById("userMenu");

        // Mobile menu toggle
        menuBtn.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
            openIcon.classList.toggle("hidden");
            closeIcon.classList.toggle("hidden");
        });

        // User dropdown toggle
        if (userBtn && userMenu) {
            userBtn.addEventListener("click", () => {
                userMenu.classList.toggle("hidden");
            });

            document.addEventListener("click", (e) => {
                if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                    userMenu.classList.add("hidden");
                }
            });
        }
    </script>
    <div class="min-h-screen bg-gray-50 text-black">

        <div>
            <main>
                {{ $slot }}
            </main>
        </div>
        <!-- Footer with Admin Link -->
        <footer class="bg-white/90 backdrop-blur mt-16">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex space-x-6 mb-4 md:mb-0">
                        <a href="#" class="text-gray-500 hover:text-teal-600">About</a>
                        <a href="{{ route('team') }}" class="text-gray-500 hover:text-teal-600">Our Team</a>
                        <a href="#" class="text-gray-500 hover:text-teal-600">Contact</a>
                        <a href="#" class="text-gray-500 hover:text-teal-600">Terms</a>
                        <a href="#" class="text-gray-500 hover:text-teal-600">Privacy</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <p class="text-sm text-gray-500">&copy; {{ date('Y') }} BiharShop. All rights reserved.
                        </p>
                        <a href="{{ route('admin.login') }}" class="text-sm text-gray-500 hover:text-teal-600">Admin
                            Login</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>

</html>
