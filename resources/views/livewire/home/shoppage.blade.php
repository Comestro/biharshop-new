<div>
    <!-- Shop Banner Section -->
    <section class="relative bg-gradient-to-r from-teal-600 via-teal-500 to-green-400">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/20"></div>

        <div class="max-w-7xl mx-auto px-6 py-20 relative z-10 flex flex-col items-center text-center text-white">
            <!-- Small Title -->
            <span class="uppercase tracking-widest text-sm font-semibold bg-white/20 px-3 py-1 rounded-full mb-4">
                Welcome to Our Store
            </span>

            <!-- Main Heading -->
            <h1 class="text-4xl sm:text-5xl font-extrabold mb-4">
                Discover Our <span class="text-yellow-300">Latest Products</span>
            </h1>

            <!-- Subtext -->
            <p class="text-lg sm:text-xl text-white/90 max-w-2xl">
                Shop the best deals, premium quality, and top-rated items – all in one place.
            </p>

            
        </div>
    </section>

    <!-- Shop Section -->
    <section class="max-w-7xl mx-auto px-6 py-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Our Products</h2>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">


            <!-- Card 1 -->
            <div class="bg-white rounded-md shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="relative p-4">
                    <span
                        class="absolute top-6 left-6 bg-teal-600 text-white text-xs font-semibold px-2 py-1 rounded-xl">25%
                        OFF</span>
                    <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=800&q=80"
                        alt="Headphones" class="w-full h-56 object-cover rounded-xl">
                </div>
                <div class="px-4 pb-4 space-y-21">
                    <h3 class="text-lg font-semibold text-gray-800">Wireless Headphones</h3>

                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-bold text-gray-800">$199</span>
                        <span class="text-sm line-through text-gray-400">$259</span>
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

                    <button
                        class="w-full mt-3 bg-teal-600 text-white py-2 rounded-lg flex items-center justify-center gap-2 font-medium hover:bg-teal-700 transition">
                        <i class="fas fa-shopping-cart"></i> Add to cart
                    </button>
                </div>
            </div>

        </div>
    </section>
</div>