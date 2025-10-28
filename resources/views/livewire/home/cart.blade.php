<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 border-b pb-3">Your Shopping Cart</h1>

    @if ($cartItems->count() > 0)
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="md:col-span-2 space-y-6">
                @foreach($cartItems as $item)
                    @php
                        $productId = $item->product_id ?? $item->id;
                        $image = $item->image ?? ($item->product->image ?? 'default.jpg');
                        $name = $item->name ?? ($item->product->name ?? 'Product');
                        $category = $item->category ?? ($item->product->category->name ?? 'N/A');
                    @endphp

                    <div
                        class="flex flex-col sm:flex-row items-center justify-between bg-white p-4 rounded-2xl shadow-md hover:shadow-lg transition">
                        <div class="flex items-center space-x-4 w-full sm:w-auto">
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $name }}"
                                class="w-24 h-24 rounded-xl object-cover border">

                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $name }}</h2>
                                <p class="text-sm text-gray-500">Category: {{ $category }}</p>

                                <div class="mt-2 flex items-center gap-3">
                                    <button wire:click="updateQty({{ $productId }}, {{ $item->qty - 1 }})"
                                        class="bg-gray-200 px-2 rounded hover:bg-gray-300">-</button>
                                    <span class="text-gray-700 font-semibold">{{ $item->qty }}</span>
                                    <button wire:click="updateQty({{ $productId }}, {{ $item->qty + 1 }})"
                                        class="bg-gray-200 px-2 rounded hover:bg-gray-300">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 sm:mt-0 flex flex-col items-end">
                            <span class="text-lg font-bold text-gray-800">₹{{ number_format($item->subtotal, 2) }}</span>
                            <button wire:click="removeItem({{ $productId }})"
                                class="text-sm text-red-600 hover:underline mt-1">Remove</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="bg-white rounded-2xl shadow-md p-6 h-fit sticky top-20">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Order Summary</h2>

                <div class="flex justify-between text-gray-600 mb-2">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($total, 2) }}</span>
                </div>

                <div class="flex justify-between text-gray-600 mb-2">
                    <span>Shipping</span>
                    <span>₹{{ $shipping }}.00</span>
                </div>

                <div class="flex justify-between text-gray-600 mb-4">
                    <span>Discount</span>
                    <span>- ₹0.00</span>
                </div>
                <div class="flex justify-between text-gray-600 mb-4">
                    <span>Tax</span>
                    <span>₹{{ $tax }}.00</span>
                </div>

                <div class="flex justify-between font-bold text-gray-900 text-lg border-t pt-3">
                    <span>Total</span>
                    <span>₹{{ number_format($subtotal, 2) }}</span>
                </div>

                @auth
                    <a href="{{ route('checkout') }}"
                        class="mt-6 inline-block w-full bg-teal-600 hover:bg-teal-700 text-white text-center py-3 rounded-xl font-semibold shadow-sm hover:shadow-md transition-all duration-200">
                        Proceed to Checkout
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="mt-6 inline-block w-full bg-teal-600 hover:bg-teal-700 text-white text-center py-3 rounded-xl font-semibold shadow-sm hover:shadow-md transition-all duration-200">
                        Proceed to Checkout
                    </a>
                @endauth

                <p class="text-xs text-gray-500 mt-2 text-center">Secure checkout — SSL encrypted</p>
            </div>
        </div>
    @else
        <!-- Full Page Empty Cart -->
        <div class="flex flex-col items-center justify-center text-center py-20">
            <div class="text-brand-600 text-5xl mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-teal-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h2 class="font-poppins text-2xl font-semibold mb-4 text-gray-800">Your cart is empty</h2>
            <p class="text-gray-600 max-w-md mx-auto mb-8">
                Looks like you haven't added any products to your cart yet. Browse our collection of premium healthy snacks.
            </p>
            <a href="/shop"
                class="bg-teal-600 text-white px-6 py-3 rounded-full hover:bg-teal-700 transition-all font-medium">
                Continue Shopping
            </a>
        </div>
    @endif
</div>