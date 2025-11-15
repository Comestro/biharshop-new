<div class="min-h-screen bg-gray-100">

    <!-- Hero Section with Search -->
    <div class="relative bg-gradient-to-br from-teal-600 via-teal-700 to-emerald-800 overflow-hidden">
      

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-10">
                <div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6">
                        Grow Your <span class="text-yellow-400">Network</span> and Earnings
                    </h1>
                    <p class="mt-3 text-lg md:text-xl text-indigo-100 max-w-xl">
                        Join BiharShop’s MLM program, invite your team, and earn daily through binary and referral commissions.
                    </p>
                    <div class="mt-8 flex items-center gap-4">
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-yellow-400 text-teal-900 font-semibold rounded-md hover:bg-yellow-300 transition">Join Network</a>
                        <a href="{{ route('shop') }}" class="px-6 py-3 bg-white/20 backdrop-blur text-white font-semibold rounded-md hover:bg-white/30 transition">Explore Products</a>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                            Daily commissions
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 7l5 5-5 5"/></svg>
                            Binary & referral
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-4h8"/></svg>
                            Easy withdrawals
                        </span>
                    </div>

                    <div class="mt-10 max-w-xl">
                        <div class="flex items-center bg-white/90 rounded-lg overflow-hidden shadow-xl p-1">
                            <div class="flex-1 px-4">
                                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search products or categories" class="w-full py-3 text-gray-800 placeholder-gray-500 border-none focus:outline-none focus:ring-0 text-lg">
                            </div>
                            <button class="px-6 py-3 bg-teal-600 text-white font-medium text-lg rounded-md hover:bg-teal-700 transition-colors duration-200 mx-1">
                                <span class="hidden md:inline">Search</span>
                                <svg class="w-6 h-6 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:block">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-indigo-100">Step 1</p>
                                <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-4h8"/></svg>
                            </div>
                            <h3 class="mt-2 text-lg font-semibold text-white">Join</h3>
                            <p class="mt-1 text-sm text-indigo-100">Register and set your referral sponsor.</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-indigo-100">Step 2</p>
                                <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5"/></svg>
                            </div>
                            <h3 class="mt-2 text-lg font-semibold text-white">Refer</h3>
                            <p class="mt-1 text-sm text-indigo-100">Grow your binary tree with direct referrals.</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-indigo-100">Step 3</p>
                                <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/></svg>
                            </div>
                            <h3 class="mt-2 text-lg font-semibold text-white">Earn</h3>
                            <p class="mt-1 text-sm text-indigo-100">Receive daily, binary, and referral commissions.</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-indigo-100">Step 4</p>
                                <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-4h8"/></svg>
                            </div>
                            <h3 class="mt-2 text-lg font-semibold text-white">Withdraw</h3>
                            <p class="mt-1 text-sm text-indigo-100">Request withdrawals with transparent charges.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 grid grid-cols-2 gap-5 md:grid-cols-4 lg:gap-8">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-6">
                    <p class="text-3xl font-bold text-white">2000+</p>
                    <p class="mt-1 text-indigo-100">Products</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-6">
                    <p class="text-3xl font-bold text-white">500+</p>
                    <p class="mt-1 text-indigo-100">Sellers</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-6">
                    <p class="text-3xl font-bold text-white">10K+</</p>
                    <p class="mt-1 text-indigo-100">Customers</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-6">
                    <p class="text-3xl font-bold text-white">24/7</p>
                    <p class="mt-1 text-indigo-100">Support</p>
                </div>
            </div>
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
                    <a href="{{ route('productview', $product->slug) }}">
                        <div class="relative p-4">
                            <span
                                class="absolute top-6 left-6 bg-teal-600 text-white text-xs font-semibold px-2 py-1 rounded-xl">25%
                                OFF</span>

                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-56 object-cover rounded-xl">

                        </div>
                    </a>
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
                            <a href="{{ route('cart', ['add' => $product->id]) }}"
                                class="flex-1 bg-teal-600 text-white py-2 rounded-lg flex items-center justify-center gap-2 font-medium hover:bg-teal-700 transition">
                                <i class="fas fa-shopping-cart"></i> Add to cart
                            </a>

                            <livewire:home.component.wishlist :products="$product->id" />

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
