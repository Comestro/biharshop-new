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

                <!-- Page Title -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">My Orders</h2>
                    <p class="text-sm text-gray-500">Showing your recent orders</p>
                </div>

                <!-- ====== ORDER CARD 1 ====== -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <!-- Header -->
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border-b border-gray-100">
                        <div class="space-y-1">
                            <h3 class="font-semibold text-gray-800 text-lg">Order #1024</h3>
                            <p class="text-sm text-gray-600">Placed on: <span class="font-medium text-gray-800">28 Oct
                                    2025</span></p>
                            <p class="text-sm text-gray-600">Payment: <span class="font-medium text-gray-800">Online
                                    (UPI)</span></p>
                        </div>
                        <div class="text-right mt-3 sm:mt-0">
                            <span
                                class="inline-block text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-semibold">Delivered</span>
                            <p class="text-sm font-semibold text-gray-800 mt-1">Total: ₹1,850</p>
                            <a href="view-order.html" class="text-sm font-medium text-teal-700 hover:underline">View
                                Order →</a>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="divide-y divide-gray-100">
                        <div class="flex flex-wrap items-center justify-between p-4 gap-4">
                            <div class="flex items-center gap-4 w-full sm:w-auto flex-1">
                                <img src="https://via.placeholder.com/70"
                                    class="w-16 h-16 rounded-lg object-cover border">
                                <div>
                                    <p class="font-semibold text-gray-800 leading-tight">Protein Powder (1kg)</p>
                                    <p class="text-sm text-gray-500">Qty: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800 text-right w-full sm:w-auto">₹1,250</p>
                        </div>

                        <div class="flex flex-wrap items-center justify-between p-4 gap-4">
                            <div class="flex items-center gap-4 w-full sm:w-auto flex-1">
                                <img src="https://via.placeholder.com/70"
                                    class="w-16 h-16 rounded-lg object-cover border">
                                <div>
                                    <p class="font-semibold text-gray-800 leading-tight">Shaker Bottle</p>
                                    <p class="text-sm text-gray-500">Qty: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800 text-right w-full sm:w-auto">₹600</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 bg-gray-50">
                        <p class="text-sm text-gray-600">Delivered on <span class="font-medium text-gray-800">27 Oct
                                2025</span></p>
                        <p class="text-sm text-gray-600">Address: <span class="font-medium text-gray-800">Patna,
                                Bihar</span></p>
                    </div>
                </div>

                <!-- ====== ORDER CARD 2 (duplicate for demo) ====== -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border-b border-gray-100">
                        <div class="space-y-1">
                            <h3 class="font-semibold text-gray-800 text-lg">Order #1025</h3>
                            <p class="text-sm text-gray-600">Placed on: <span class="font-medium text-gray-800">25 Oct
                                    2025</span></p>
                            <p class="text-sm text-gray-600">Payment: <span class="font-medium text-gray-800">COD</span>
                            </p>
                        </div>
                        <div class="text-right mt-3 sm:mt-0">
                            <span
                                class="inline-block text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full font-semibold">Pending</span>
                            <p class="text-sm font-semibold text-gray-800 mt-1">Total: ₹999</p>
                            <a href="view-order.html" class="text-sm font-medium text-teal-700 hover:underline">View
                                Order →</a>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between p-4 gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto flex-1">
                            <img src="https://via.placeholder.com/70" class="w-16 h-16 rounded-lg object-cover border">
                            <div>
                                <p class="font-semibold text-gray-800 leading-tight">Gym Gloves</p>
                                <p class="text-sm text-gray-500">Qty: 1</p>
                            </div>
                        </div>
                        <p class="font-semibold text-gray-800 text-right w-full sm:w-auto">₹999</p>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 bg-gray-50">
                        <p class="text-sm text-gray-600">Expected Delivery: <span class="font-medium text-gray-800">31
                                Oct 2025</span></p>
                        <p class="text-sm text-gray-600">Address: <span class="font-medium text-gray-800">Gandhi Nagar,
                                Patna</span></p>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>