<div class="min-h-screen bg-gray-100">

    <!-- Hero Section with Search -->
    <div class="relative bg-gradient-to-br from-teal-600 via-teal-700 to-emerald-800 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" />
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
                            <input wire:model.live.debounce.300ms="search" type="search"
                                placeholder="What are you looking for?"
                                class="w-full py-3 text-gray-800 placeholder-gray-500 border-none focus:outline-none focus:ring-0 text-lg">
                        </div>
                        <button
                            class="px-8 py-3 bg-teal-600 text-white font-medium text-lg rounded-md hover:bg-teal-700 transition-colors duration-200 mx-1">
                            <span class="hidden md:inline">Search</span>
                            <svg class="w-6 h-6 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
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

    <!-- Products Grid Section -->
    <div class="max-w-7xl mx-auto px-8 sm:px-6 lg:px-8 pb-10">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
               
                <div class="bg-white rounded-md shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="relative p-4">
                        <span
                            class="absolute top-6 left-6 bg-teal-600 text-white text-xs font-semibold px-2 py-1 rounded-xl">25%
                            OFF</span>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                            class="w-full h-56 object-cover rounded-xl">
                    </div>
                    <div class="px-4 pb-4 space-y-21">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>

                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-gray-800">₹{{ $product->price }}</span>
                            <span class="text-xs line-through text-gray-400">₹{{ $product->price }}</span>
                        </div>

                        <!-- Ratings -->
                        <div class="flex items-center space-x-1">
                            <div class="flex text-yellow-400 text-sm">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= 4.5)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span
                                class="text-sm text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded-md font-semibold">4.5</span>
                        </div>
                        <div class="flex items-center gap-3 mt-3">
                            <!-- Add to Cart Button -->
                            <button
                                class="flex-1 bg-teal-600 text-white py-2 rounded-lg flex items-center justify-center gap-2 font-medium hover:bg-teal-700 transition">
                                <i class="fas fa-shopping-cart"></i> Add to cart
                            </button>

                            <!-- Like Button -->
                            <button
                                class="w-12 h-11 flex items-center justify-center bg-gray-100 text-red-500 rounded-lg hover:bg-red-100 transition">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>


                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">No products found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>



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