<div class="max-w-7xl mx-auto px-6 py-10  min-h-screen">
    <!-- Breadcrumb -->
    <div class="text-sm text-gray-600 mb-6">
        <a href="/" class="hover:text-teal-600">Home</a>
        <span class="mx-2 text-gray-400">›</span>
        <a href="/cart" class="hover:text-teal-600">Cart</a>
        <span class="mx-2 text-gray-400">›</span>
        <span class="text-teal-600 font-medium">Checkout</span>
    </div>

    <!-- Heading -->
    <h1 class="text-3xl font-bold mb-8 text-gray-800 border-b border-gray-200 pb-3">Checkout</h1>

    <div class="grid md:grid-cols-3 gap-8">
        <!-- Left -->
        <div class="md:col-span-2 space-y-8">
            <!-- Login / Contact Info -->
            <div
                class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition p-5 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <!-- Step Number -->
                    <div
                        class="w-7 h-7 flex items-center justify-center border border-teal-600 text-white font-semibold rounded-md text-sm bg-teal-600">
                        1
                    </div>

                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Login</h2>
                            <span class="text-teal-600 text-xs font-bold">✓</span>
                        </div>

                        <p class="mt-1 text-base font-semibold text-gray-900">
                            {{ Auth::user()->name  }}
                            <span class="ml-2 text-gray-600 font-normal">
                                {{ Auth::user()->email }}
                            </span>
                        </p>
                    </div>
                </div>

                <button
                    class="text-teal-700 font-semibold border border-teal-600 px-4 py-1.5 rounded-md hover:bg-teal-600 hover:text-white transition text-sm">
                    CHANGE
                </button>
            </div>


            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-5 border-b border-gray-200 pb-5">
                    <div class="flex gap-3">
                        <div
                            class="w-7 h-7 flex items-center justify-center border border-teal-600  font-semibold rounded-md text-sm  {{ $selectedAddressId ? 'bg-teal-600 text-white' : 'text-teal-600' }}">
                            2
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800"> Address</h2>
                        @if ($selectedAddressId)
                            <span class="text-teal-600 text-xs font-bold mt-1.5">✓</span>
                        @endif

                    </div>
                    @if ($selectedAddressId)

                        <button wire:click="nullAddressId"
                            class="text-teal-700 font-semibold border border-teal-600 px-4 py-1.5 rounded-md hover:bg-teal-600 hover:text-white transition text-sm">
                            CHANGE
                        </button>
                    @else

                        <button wire:click="openAddModel"
                            class="text-teal-700 font-semibold border border-teal-600 px-4 py-1.5 rounded-md hover:bg-teal-600 hover:text-white transition text-sm">
                            + Add New
                        </button>

                    @endif

                </div>

                <div class="grid sm:grid-cols-2 gap-5 max-h-48 overflow-y-auto p-2">
                    @forelse ($addresses as $address)
                        <label wire:click="selectAddress({{ $address->id }})"
                            class="block rounded-xl p-4 cursor-pointer shadow-md relative group transition hover:shadow-lg {{ $selectedAddressId === $address->id ? 'border-2 border-teal-500 bg-teal-50' : 'border border-gray-300' }}">

                            <div class="pr-6">
                                <h3 class="font-semibold text-gray-800">
                                    {{ $address->city ?? 'Unknown City' }}
                                </h3>

                                <p class="text-sm text-gray-600 mt-1 leading-snug">
                                    {{ $address->address_line1 ?? 'n/a' }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    Mobile: {{ $address->phone ?? '+91 98765 43210' }}
                                </p>
                            </div>

                            <!-- Hidden Radio Input -->
                            <input type="radio" class="hidden" wire:model="selectedAddressId">
                        </label>
                    @empty
                        <div
                            class="border border-gray-300 rounded-xl p-4 bg-gray-50 text-gray-400 cursor-not-allowed opacity-60 flex items-center justify-center">
                            No Address Available
                        </div>
                    @endforelse
                </div>


            </div>


            <!-- Products -->
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6 border border-gray-100">

                <div class="flex justify-between items-center border-gray-200 ">
                    <div class="flex gap-3">
                        <div
                            class="w-7 h-7 flex items-center justify-center border border-teal-600  font-semibold rounded-md text-sm {{ $selectedAddressId ? 'bg-teal-600 text-white' : 'text-teal-600' }}">
                            2
                        </div>
                        <h2 class="text-lg font-semibold  text-gray-800  ">Your Order</h2>
                        @if ($selectedAddressId)
                            <span class="text-teal-600 text-xs font-bold mt-1.5">✓</span>
                        @endif
                    </div>


                </div>

                @if ($selectedAddressId)
                    <div class="divide-y divide-gray-100 border-t border-gray-200 mt-5">
                        @foreach ($cartItem as $item)
                            <div class="flex items-center justify-between py-4">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('productview', $item->product->slug) }}">
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                            class="w-16 h-16 rounded-lg border border-gray-200 object-cover" alt="">
                                    </a>
                                    <div>
                                        <p class="font-medium text-gray-800"> {{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-500">Qty: {{ $item->qty }}</p>
                                    </div>
                                </div>
                                <span class="font-semibold text-gray-800">₹{{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if ($selectedAddressId)
                    <div class="divide-y divide-gray-100 border-t border-gray-200 mt-5">
                        <div class="flex items-center py-2">
                            <div class="ml-auto">
                                <button wire:click="continueToPayment"
                                    class="text-teal-700 font-semibold border border-teal-600 px-4 py-1.5 rounded-md hover:bg-teal-600 hover:text-white transition text-sm">
                                    CONTINUE
                                </button>
                            </div>
                        </div>
                    </div>
                @endif




            </div>

            <!-- Payment -->
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6 border border-gray-100">
                <div class="flex gap-3">
                    <div
                        class="w-7 h-7 flex items-center justify-center border border-teal-600 text-teal-600 font-semibold rounded-md text-sm {{ $paymentMethod ? 'bg-teal-600 text-white' : 'text-teal-600' }}">
                        4
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800  border-gray-200 pb-2">Payment Method
                    </h2>
                    @if ($paymentMethod)
                        <span class="text-teal-600 text-xs font-bold mt-1.5">✓</span>

                    @endif
                </div>
                @if ($continueToPaymentSection)
                    <div class="space-y-4 mt-5">
                        <label wire:click="selectPaymentMethod({{ 'cod' }})"
                            class="flex items-center gap-3 rounded-xl p-4 hover:border-teal-500 cursor-pointer transition {{ $paymentMethod == "cod" ? 'border-2 border-teal-500 bg-teal-50' : 'border border-gray-200 ' }}">
                            <input type="radio" class="hidden" value="cod" wire:model.live="paymentMethod">

                            <div>
                                <p class="font-medium text-gray-800">Cash on Delivery (COD)</p>
                                <p class="text-sm text-gray-500">Pay when your order is delivered.</p>
                            </div>
                        </label>

                        <label wire:click="selectPaymentMethod({{ 'online' }})"
                            class="flex items-center gap-3 rounded-xl p-4 hover:border-teal-500 cursor-pointer transition {{ $paymentMethod == "online" ? 'border-2 border-teal-500 bg-teal-50' : 'border border-gray-200 ' }}">
                            <input type="radio" class="hidden" value="online" wire:model.live="paymentMethod">
                            <div>
                                <p class="font-medium text-gray-800">Online Payment</p>
                                <p class="text-sm text-gray-500">Pay securely using UPI / Card / Net Banking.</p>
                            </div>
                        </label>
                    </div>
                @endif

            </div>

            <!-- Notes -->
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6 border border-gray-100">
                <h2 class="text-lg font-semibold mb-5 text-gray-800 border-b border-gray-200 pb-2">Order Notes</h2>
                <textarea rows="3" placeholder="Write any special instructions for delivery..."
                    class="w-full border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 px-3 py-2.5"></textarea>
            </div>
        </div>

        <!-- Right (Summary) -->
        <div
            class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6 h-fit sticky top-24 border border-gray-100">
            <h2 class="text-lg font-semibold mb-5 text-gray-800 border-b border-gray-200 pb-2">Order Summary</h2>

            <div class="space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>₹{{ $subtotal }}.00</span>
                </div>
                <div class="flex justify-between">
                    <span>Shipping</span>
                    @if ($shipping === 0)
                        <span class="text-green-600 font-medium">Free</span>
                    @else
                        <span class="text-green-600 font-medium">{{ $shipping }}</span>
                    @endif
                </div>
                <div class="flex justify-between">
                    <span>Tax (GST)</span>
                    <span>₹{{ $tax }}</span>
                </div>
                @if ($couponApplied)
                    <div class="flex justify-between">
                        <span>Coupon Discount</span>
                        <span>₹{{ $couponDiscount }}</span>
                    </div>
                @endif

                <div class="flex justify-between border-t border-gray-200 pt-3 text-base font-semibold text-gray-900">
                    <span>Total</span>
                    <span>₹{{ $total }}.00</span>
                </div>
            </div>

            <!-- Coupon Section -->
            <div class="border-t border-gray-200 pt-5 mt-5" x-data="{ open: false }">
                <!-- Header -->
                <div class="flex ">
                    <input type="text" placeholder="Enter Coupon Code" wire:model="couponCode"
                        class="flex-1 border border-gray-300 rounded-l-lg focus:ring-teal-500 focus:border-teal-500 px-3 py-2.5">

                    @if($couponApplied)
                        <button type="button" wire:click="removeCoupon"
                            class="bg-teal-600 text-white px-4 py-2.5 rounded-r-lg hover:bg-teal-700 transition font-medium">
                            Remove
                        </button>
                    @else
                        <button type="button" wire:click="applyCoupon"
                            class="bg-teal-600 text-white px-4 py-2.5 rounded-r-lg hover:bg-teal-700 transition font-medium">
                            Apply
                        </button>
                    @endif
                </div>
                <!-- Messages -->
                @if($couponError)
                    <div class="mt-1 text-red-600 text-xs px-2">{{ $couponError }}</div>
                @elseif($couponApplied)
                    <div class="mt-1 text-green-600 text-xs px-2">Coupon applied successfully!</div>
                @endif
                @if (!$couponApplied)
                    <button type="button" @click="open = !open"
                        class="flex justify-between w-full text-left text-sm font-medium text-gray-700 mb-2 px-3 py-2 border rounded-lg hover:bg-gray-50 transition mt-2">
                        Have a Coupon?
                        <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transform transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Expandable Coupon List -->
                    <div x-show="open" x-transition class="mt-1">

                        <div class="mt-2 bg-gray-50 border border-gray-200 rounded-lg p-2">
                            @if($allCoupons->count())
                                <p class="text-xs font-semibold text-gray-500 mb-1 px-2">Available Coupons:</p>
                                <div class="flex flex-col space-y-1">
                                    @foreach ($allCoupons as $allCoupon)
                                        <button type="button" wire:click="applyCouponFromOrderSummery('{{ $allCoupon->code }}')"
                                            class="flex justify-between items-center px-3 py-2 bg-white rounded border hover:bg-gray-100 text-sm text-gray-700 font-medium transition">
                                            <span>{{ $allCoupon->code }}</span>
                                            <span
                                                class="text-teal-600 font-semibold">{{ $allCoupon->discount_value }}{{ $allCoupon->discount_type == 'percent' ? '%' : '₹' }}
                                                Off</span>
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-500 px-2">No available coupons at the moment.</p>
                            @endif
                        </div>


                    </div>
                @endif

            </div>



            <!-- Place Order -->
            <div class="pt-6">
                @if ($paymentMethod == "cod")
                    <button wire:click="placeOrder"
                        class="w-full bg-teal-600 text-white py-3.5 rounded-lg font-semibold hover:bg-teal-700 transition">
                        Place Order Securely
                    </button>
                @elseif($paymentMethod == "online")
                    <button wire:click="placeOrder"
                        class="w-full bg-teal-600 text-white py-3.5 rounded-lg font-semibold hover:bg-teal-700 transition">
                        Pay Online Payment
                    </button>
                @endif

                <p class="text-xs text-center text-gray-500 mt-2">
                    Your data is protected with SSL encryption
                </p>
            </div>
        </div>
    </div>
    @if ($isOpenAddModel == true)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6 relative">
                <button wire:click="closeAddModel"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                <h2 class="text-2xl font-semibold mb-5 text-gray-800">Add New Address</h2>
                <form wire:submit="addAddress">
                    <div class="space-y-4">
                        <div>
                            <input type="text" wire:model.live="address_line1" placeholder="Address Line 1"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            @error('address_line1')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <input type="text" wire:model.live="near_by" placeholder="Near By (optional)"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            @error('near_by')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="text" wire:model.live="city" placeholder="City"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="text" wire:model.live="state" placeholder="State"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                @error('state')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="text" wire:model.live="country" placeholder="Country"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                @error('country')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="text" wire:model.live="postal_code" placeholder="Postal Code"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                @error('postal_code')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <input type="text" wire:model.live="phone" placeholder="Phone (optional)"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full bg-teal-500 text-white rounded-lg py-2 font-semibold hover:bg-teal-600 transition">
                            Save Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


</div>