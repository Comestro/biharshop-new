<div class="min-h-screen bg-gray-100">
    <!-- Header/Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <span class="text-2xl font-bold text-indigo-600">BiharShop</span>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600">Home</a>
                    <a href="#products" class="text-gray-700 hover:text-indigo-600">Products</a>
                    <a href="#about" class="text-gray-700 hover:text-indigo-600">About</a>
                    <a href="#contact" class="text-gray-700 hover:text-indigo-600">Contact</a>
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
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Login</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
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
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold sm:text-5xl md:text-6xl">
                    Find Everything You Need
                </h1>
                <p class="mt-4 text-xl">Search through thousands of products at the best prices.</p>
                
                <!-- Search Bar -->
                <div class="mt-8 max-w-3xl mx-auto">
                    <div class="flex items-center bg-white rounded-lg overflow-hidden shadow-lg">
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="search" 
                            placeholder="Search for products..."
                            class="flex-1 px-6 py-4 text-gray-700 focus:outline-none">
                        <button class="px-6 py-4 bg-indigo-600 text-white font-medium hover:bg-indigo-700">
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    @if($categories->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex space-x-4 overflow-x-auto pb-4 scrollbar-hide">
            <button wire:click="$set('selectedCategory', null)" 
                    class="flex-none px-4 py-2 rounded-full {{ !$selectedCategory ? 'bg-indigo-600 text-white' : 'bg-white hover:bg-gray-50' }}">
                All Products
            </button>
            @foreach($categories as $category)
                <button wire:click="$set('selectedCategory', {{ $category->id }})"
                        class="flex-none px-4 py-2 rounded-full {{ $selectedCategory == $category->id ? 'bg-indigo-600 text-white' : 'bg-white hover:bg-gray-50' }}">
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
                        <button class="mt-4 w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200">
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
                    <a href="#" class="text-gray-500 hover:text-indigo-600">About</a>
                    <a href="#" class="text-gray-500 hover:text-indigo-600">Contact</a>
                    <a href="#" class="text-gray-500 hover:text-indigo-600">Terms</a>
                    <a href="#" class="text-gray-500 hover:text-indigo-600">Privacy</a>
                </div>
                <div class="flex items-center space-x-4">
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} BiharShop. All rights reserved.</p>
                    <a href="{{ route('admin.login') }}" class="text-sm text-gray-500 hover:text-indigo-600">Admin Login</a>
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
