<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 font-sans text-gray-800">

    <!-- 🧭 Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-8">
        <ol class="flex items-center space-x-1 md:space-x-2">
            <li><a href="/" class="hover:text-teal-600">Home</a></li>
            <li>/</li>
            <li><a href="/shop" class="hover:text-teal-600">Shop</a></li>
            <li>/</li>
            <li class="text-gray-700 font-semibold truncate">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

        <!-- 🖼️ Sticky Image Section -->
        <div class="lg:col-span-2">
            <div class="sticky top-28 space-y-4">
                <!-- Main Image -->
                <div class="relative rounded-2xl overflow-hidden border border-gray-100 shadow-lg group">
                    <img src="{{ asset('storage/' . $bigimage) }}" alt="{{ $product->name }}"
                        class="w-full h-[480px] object-cover transition-transform duration-300 group-hover:scale-105">
                    <span
                        class="absolute top-4 left-4 bg-teal-600 text-white px-3 py-1 text-xs font-bold rounded-full shadow">Bestseller</span>
                </div>

                <!-- Thumbnails -->
                <div class="flex gap-3 overflow-x-auto">
                    @foreach([$product->image, $product->image1, $product->image2, $product->image3] as $img)
                        @if($img)
                            <button wire:click="changeImage('{{ $img }}')"
                                class="rounded-lg border border-gray-200 hover:border-teal-500 transition flex-shrink-0">
                                <img src="{{ asset('storage/' . $img) }}" class="w-20 h-20 object-cover rounded-lg"
                                    alt="Thumbnail">
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 space-y-6">

            <!-- Header: title + rating + small meta row -->
            <header class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>

                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= ($product->rating ?? 4))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="ml-1 text-gray-500">({{ $product->review_count ?? 245 }})</span>
                    </div>

                    <span class="mx-1">•</span>

                    <div class="flex items-center gap-2">
                        <i class="fas fa-box-open text-teal-600"></i>
                        <span>{{ $product->sold_this_month ?? 124 }} sold this month</span>
                    </div>

                    <span class="mx-1 hidden md:inline">•</span>

                    <div class="hidden md:flex items-center gap-2">
                        <i class="fas fa-store text-gray-500"></i>
                        <span class="text-gray-700">{{ $product->seller_name ?? 'Yours Snacks Store' }}</span>
                    </div>
                </div>
            </header>

            <!-- Price + quick stats row -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <div class="flex items-end gap-3">
                        <div class="text-3xl sm:text-4xl font-extrabold text-teal-700">
                            ₹{{ number_format($product->price, 0) }}</div>
                        <div class="text-sm line-through text-gray-400">
                            ₹{{ number_format($product->mrp ?? ($product->price + 1000), 0) }}</div>
                        <div class="ml-2 px-2 py-0.5 bg-teal-100 text-teal-700 text-xs font-semibold rounded">Save
                            {{ $product->discount_percent ?? 35 }}%
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Inclusive of all taxes</div>
                </div>

                <div class="flex gap-3 text-sm text-gray-600">
                    <div class="flex items-center gap-2 bg-gray-50 border rounded-lg px-3 py-2">
                        <i class="fas fa-boxes text-gray-500"></i>
                        <div class="text-left">
                            <div class="text-xs text-gray-500">In stock</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 bg-gray-50 border rounded-lg px-3 py-2">
                        <i class="fas fa-bolt text-orange-500"></i>
                        <div class="text-left">
                            <div class="text-xs text-gray-500">5K+ bought in past month</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery & ETA (compact) -->
            <div class="flex items-start gap-3 bg-green-50 border border-green-200 rounded-lg p-4 text-sm">
                <i class="fas fa-truck text-green-600 text-xl mt-0.5"></i>
                <div>
                    <div class="font-semibold text-gray-900">Delivery by <span
                            class="text-green-700">{{ now()->addDays(4)->format('D, d M') }}</span></div>
                    <div class="text-gray-600">Order within <span class="font-medium">5 hrs 48 mins</span> for earliest
                        delivery • <span class="font-medium">Free</span> delivery over ₹500</div>
                    <div class="text-xs text-gray-500 mt-1">Fulfilled by <span
                            class="text-gray-800 font-medium">{{ $product->fulfillment ?? 'Yours Snacks Fulfillment' }}</span>
                    </div>
                </div>
            </div>

            <!-- Offers (single compact card) -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 font-semibold text-yellow-800">
                        <i class="fas fa-gift"></i> Offers & Promotions
                    </div>
                    <div class="text-xs text-gray-500">View all</div>
                </div>
                <ul class="mt-3 space-y-1 text-gray-700">
                    <li>• Extra 5% off with UPI (applied at checkout)</li>
                    <li>• No-cost EMI on orders above ₹3,000</li>
                    <li>• Exchange available — get up to ₹500 off</li>
                </ul>
            </div>

            <!-- ✨ Product Highlights (Optimized & Responsive) -->
            <section class="bg-white border rounded-xl p-5 text-sm shadow-sm">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2 text-base sm:text-lg">
                    <i class="fas fa-list-alt text-teal-600"></i> Product Highlights
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
                    <!-- Category -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-tag text-teal-500 mt-1"></i>
                        <div>
                            <div class="text-xs text-gray-500">Category</div>
                            <div class="font-medium">{{ $product->category->name ?? 'Apparel' }}</div>
                        </div>
                    </div>

                    <!-- Material -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-tshirt text-teal-500 mt-1"></i>
                        <div>
                            <div class="text-xs text-gray-500">Material</div>
                            <div class="font-medium">{{ $product->material ?? 'N\a' }}</div>
                        </div>
                    </div>

                    <!-- Sizes -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-ruler-combined text-teal-500 mt-1"></i>
                        <div>
                            <div class="text-xs text-gray-500">Sizes</div>
                            <div class="font-medium">{{ $product->size ?? 'N\a' }}</div>
                        </div>
                    </div>

                    <!-- Color -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-palette text-teal-500 mt-1"></i>
                        <div>
                            <div class="text-xs text-gray-500">Color</div>
                            <div class="font-medium">{{ $product->color ?? 'N\a' }}</div>
                        </div>
                    </div>

                    <!-- Brand -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-industry text-teal-500 mt-1"></i>
                        <div>
                            <div class="text-xs text-gray-500">Brand</div>
                            <div class="font-medium">{{ $product->brand ?? 'N\a' }}</div>
                        </div>
                    </div>


                    <!-- Return Policy -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-undo text-teal-500 mt-1"></i>
                        <div>
                            <div class="text-xs text-gray-500">Return Policy</div>
                            <div class="font-medium">7 Days Easy Return</div>
                        </div>
                    </div>

                    <!-- Warranty -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-shield-alt text-teal-500 mt-1"></i>
                        <div>
                            <div class="text-xs text-gray-500">Warranty</div>
                            <div class="font-medium">1 Year</div>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-boxes text-teal-500 mt-1"></i>
                        <div>
                            <div class="text-xs text-gray-500">Stock Status</div>
                            <div class="font-medium">{{ $product->stock ?? 56 }} units available</div>
                        </div>
                    </div>

                    <!-- Sold This Month -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-chart-line text-teal-500 mt-1"></i>
                        <div>
                            <div class="text-xs text-gray-500">Sold This Month</div>
                            <div class="font-medium">{{ $product->sold_this_month ?? 124 }}</div>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-truck text-teal-500 mt-1"></i>
                        <div>
                            <div class="text-xs text-gray-500">Shipping</div>
                            <div class="font-medium">Free delivery over ₹500 • {{ now()->addDays(4)->format('D, d M') }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Action Row: Quantity + Add to Cart / Buy Now (prominent, aligned) -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">

                <div class="flex-1 flex gap-3">
                    <a href="{{ route('cart', ['add' => $product->id]) }}"
                        class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-gray-900 py-3 rounded-lg font-semibold flex items-center justify-center gap-2 shadow">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </a>

                    <button wire:click="buyNow"
                        class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-semibold flex items-center justify-center gap-2 shadow">
                        <i class="fas fa-bolt"></i> Buy Now
                    </button>
                </div>
            </div>

            <!-- Small badges row -->
            <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                <div class="flex items-center gap-2 bg-gray-50 border rounded-lg px-3 py-2">
                    <i class="fas fa-shipping-fast text-teal-600"></i> Free Delivery
                </div>
                <div class="flex items-center gap-2 bg-gray-50 border rounded-lg px-3 py-2">
                    <i class="fas fa-sync text-teal-600"></i> 7-Day Return
                </div>
                <div class="flex items-center gap-2 bg-gray-50 border rounded-lg px-3 py-2">
                    <i class="fas fa-lock text-teal-600"></i> Secure Payment
                </div>
                <div class="flex items-center gap-2 bg-gray-50 border rounded-lg px-3 py-2">
                    <i class="fas fa-certificate text-teal-600"></i> Certified Seller
                </div>
            </div>

            <!-- Manufacturer / SKU small -->
            <div class="text-sm text-gray-600 mt-2 border-t pt-3">
                <div>Manufactured By: <span
                        class="font-medium text-gray-800">{{ $product->manufacturer ?? 'Yours Snacks Pvt. Ltd.' }}</span>
                </div>
                <div>SKU: <span class="font-medium text-gray-800">{{ $product->sku ?? 'YSN-PRD-2025' }}</span></div>
            </div>

        </div>

    </div>
    <!-- Products Grid Section -->
    <div class=" mx-auto pb-10 mt-20">
        <h1 class="text-xl font-medium">Customers who bought this item also bought</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-5">
            @forelse($relatedProducts as $product)

                <div class="bg-white rounded-md shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="relative p-4">
                        <span
                            class="absolute top-6 left-6 bg-teal-600 text-white text-xs font-semibold px-2 py-1 rounded-xl">25%
                            OFF</span>
                        <a href="{{ route('productview', $product->slug) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-56 object-cover rounded-xl">
                        </a>
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


    </div>
</div>