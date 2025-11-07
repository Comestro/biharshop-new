<!-- Add Font Awesome CDN -->
<!-- <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script> -->

<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div
        class="max-w-6xl mx-auto bg-white rounded-xl shadow border border-gray-200 flex flex-col md:flex-row overflow-hidden">

        <!-- Sidebar -->
        <livewire:user.component.sidebar />


        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-50 overflow-y-auto">
            <div class="max-w-5xl mx-auto space-y-6">

                <!-- Header -->
                <div
                    class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Order #1024</h2>
                        <p class="text-sm text-gray-500">Placed on: <span class="font-medium text-gray-800">28 Oct
                                2025</span></p>
                        <p class="text-sm text-gray-500">Payment: <span class="font-medium text-gray-800">Online
                                (UPI)</span></p>
                    </div>
                    <div class="mt-3 sm:mt-0 text-right">
                        <span
                            class="inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-semibold">Delivered</span>
                        <p class="text-sm font-medium text-gray-700 mt-2">Delivered on 27 Oct 2025</p>
                    </div>
                </div>

                <!-- Address & Payment Info -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 grid sm:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-gray-800 font-semibold mb-2">Delivery Address</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Ankur Jha<br>
                            12, Gandhi Nagar, Patna<br>
                            Bihar, India - 800001<br>
                            Phone: +91 98765 43210
                        </p>
                    </div>
                    <div>
                        <h3 class="text-gray-800 font-semibold mb-2">Payment Information</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Payment Method: <span class="font-medium text-gray-800">UPI</span><br>
                            Transaction ID: <span class="font-medium text-gray-800">TXN8756342</span><br>
                            Total Amount: <span class="font-medium text-gray-800">₹1,850</span>
                        </p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800 text-lg">Order Items</h3>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <!-- Item 1 -->
                        <div class="flex flex-wrap items-center justify-between p-4 gap-4">
                            <div class="flex items-center gap-4 flex-1 min-w-[200px]">
                                <img src="https://via.placeholder.com/80"
                                    class="w-20 h-20 rounded-lg object-cover border">
                                <div>
                                    <p class="font-semibold text-gray-800 leading-tight">Protein Powder (1kg)</p>
                                    <p class="text-sm text-gray-500">Qty: 1</p>
                                    <p class="text-sm text-gray-500">Category: Supplements</p>
                                </div>
                            </div>
                            <div class="text-right min-w-[100px]">
                                <p class="font-medium text-gray-800">₹1,250</p>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="flex flex-wrap items-center justify-between p-4 gap-4">
                            <div class="flex items-center gap-4 flex-1 min-w-[200px]">
                                <img src="https://via.placeholder.com/80"
                                    class="w-20 h-20 rounded-lg object-cover border">
                                <div>
                                    <p class="font-semibold text-gray-800 leading-tight">Shaker Bottle</p>
                                    <p class="text-sm text-gray-500">Qty: 1</p>
                                    <p class="text-sm text-gray-500">Category: Accessories</p>
                                </div>
                            </div>
                            <div class="text-right min-w-[100px]">
                                <p class="font-medium text-gray-800">₹600</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-800 text-lg mb-3">Order Summary</h3>
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex justify-between"><span>Subtotal</span><span>₹1,850</span></div>
                        <div class="flex justify-between"><span>Shipping</span><span>₹0.00</span></div>
                        <div class="flex justify-between font-semibold border-t pt-2">
                            <span>Total</span><span>₹1,850</span></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 flex flex-wrap gap-3 justify-end">
                    <button class="bg-teal-700 text-white text-sm px-4 py-2 rounded-lg hover:bg-teal-800 transition">
                        <i class="fa-solid fa-file-invoice"></i> Download Invoice
                    </button>
                    <button class="bg-teal-700 text-white text-sm px-4 py-2 rounded-lg hover:bg-teal-800 transition">
                        <i class="fa-solid fa-star"></i> Write Review
                    </button>
                    <button class="bg-red-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        <i class="fa-solid fa-xmark"></i> Cancel Order
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>