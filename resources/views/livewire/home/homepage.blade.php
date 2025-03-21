<div class="min-h-screen bg-gray-100">
    <!-- Header/Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <span class="text-2xl font-bold text-teal-600">BiharShop</span>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-teal-600">Home</a>
                    <a href="#products" class="text-gray-700 hover:text-teal-600">Products</a>
                    @auth
                        @if(auth()->user()->membership)
                            <a href="{{ route('member.dashboard') }}" class="text-gray-700 hover:text-teal-600">Member Panel</a>
                        @else
                            <a href="{{ route('membership.register') }}" class="text-gray-700 hover:text-teal-600">Become Member</a>
                        @endif
                        @if(auth()->guard('admin')->check())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-teal-600">Admin Panel</a>
                        @endif
                    @endauth
                    <a href="#about" class="text-gray-700 hover:text-teal-600">About</a>
                    <a href="#contact" class="text-gray-700 hover:text-teal-600">Contact</a>
                </div>

                <!-- Right Side Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Cart -->
                    <button class="relative p-2 hover:bg-gray-100 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">0</span>
                    </button>

                    <!-- Auth Links -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm">
                                <span class="mr-2">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                @if(auth()->user()->membership)
                                    <a href="{{ route('member.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Member Panel</a>
                                @else
                                    <a href="{{ route('membership.register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Become Member</a>
                                @endif
                                @if(auth()->guard('admin')->check())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Panel</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-teal-600">Login</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700">
                            Sign Up
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button type="button" class="md:hidden p-2 rounded-md hover:bg-gray-100" @click="mobileMenu = true">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Search -->
    <div class="relative bg-gradient-to-br from-teal-600 via-teal-700 to-emerald-800 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)"/>
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6">
                    Welcome to <span class="text-yellow-400">BiharShop</span>
                </h1>
                <p class="mt-4 text-xl md:text-2xl text-indigo-100 max-w-2xl mx-auto">
                    Discover a world of authentic products at unbeatable prices.
                </p>
                
                <!-- Search Bar -->
                <div class="mt-10 max-w-3xl mx-auto">
                    <div class="flex items-center bg-white rounded-lg overflow-hidden shadow-xl p-1">
                        <div class="flex-1 px-4">
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="search" 
                                placeholder="What are you looking for?"
                                class="w-full py-3 text-gray-800 placeholder-gray-500 border-none focus:outline-none focus:ring-0 text-lg">
                        </div>
                        <button class="px-8 py-3 bg-teal-600 text-white font-medium text-lg rounded-md hover:bg-teal-700 transition-colors duration-200 mx-1">
                            <span class="hidden md:inline">Search</span>
                            <svg class="w-6 h-6 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Popular Searches -->
                    <div class="mt-4 flex items-center justify-center space-x-4 text-sm text-indigo-100">
                        <span>Popular:</span>
                        <a href="#" class="hover:text-white">Electronics</a>
                        <a href="#" class="hover:text-white">Fashion</a>
                        <a href="#" class="hover:text-white">Home</a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="mt-16 grid grid-cols-2 gap-5 md:grid-cols-4 lg:gap-8">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-6">
                        <p class="text-3xl font-bold text-white">2000+</p>
                        <p class="mt-1 text-indigo-100">Products</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-6">
                        <p class="text-3xl font-bold text-white">500+</p>
                        <p class="mt-1 text-indigo-100">Sellers</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-6">
                        <p class="text-3xl font-bold text-white">10K+</p>
                        <p class="mt-1 text-indigo-100">Customers</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-6">
                        <p class="text-3xl font-bold text-white">24/7</p>
                        <p class="mt-1 text-indigo-100">Support</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Shape Divider -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="fill-gray-100" viewBox="0 0 1920 80" preserveAspectRatio="none">
                <path d="M0,0 C480,80 960,80 1440,80 C1920,80 1920,80 1920,80 L1920,0 L0,0 Z"></path>
            </svg>
        </div>
    </div>

    <!-- Categories -->
    @if($categories->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex space-x-4 overflow-x-auto pb-4 scrollbar-hide">
            <button wire:click="$set('selectedCategory', null)" 
                    class="flex-none px-4 py-2 rounded-full {{ !$selectedCategory ? 'bg-teal-600 text-white' : 'bg-white hover:bg-gray-50' }}">
                All Products
            </button>
            @foreach($categories as $category)
                <button wire:click="$set('selectedCategory', {{ $category->id }})"
                        class="flex-none px-4 py-2 rounded-full {{ $selectedCategory == $category->id ? 'bg-teal-600 text-white' : 'bg-white hover:bg-gray-50' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover rounded-t-lg">
                    @endif
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                        <p class="mt-1 text-gray-600">₹{{ number_format($product->price, 2) }}</p>
                        <button class="mt-4 w-full bg-teal-600 text-white py-2 rounded-md hover:bg-teal-700 transition-colors duration-200">
                            Add to Cart
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No products found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Footer with Admin Link -->
    <footer class="bg-white border-t mt-16">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex space-x-6 mb-4 md:mb-0">
                    <a href="#" class="text-gray-500 hover:text-teal-600">About</a>
                    <a href="#" class="text-gray-500 hover:text-teal-600">Contact</a>
                    <a href="#" class="text-gray-500 hover:text-teal-600">Terms</a>
                    <a href="#" class="text-gray-500 hover:text-teal-600">Privacy</a>
                </div>
                <div class="flex items-center space-x-4">
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} BiharShop. All rights reserved.</p>
                    <a href="{{ route('admin.login') }}" class="text-sm text-gray-500 hover:text-teal-600">Admin Login</a>
                </div>
            </div>
        </div>
    </footer>
</div>

@push('styles')
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush
