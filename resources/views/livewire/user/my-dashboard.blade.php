<!-- Font Awesome CDN -->
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
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Dashboard Overview</h2>

                <!-- Example Content Boxes -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-700">Total Orders</h3>
                        <p class="text-2xl font-bold text-teal-700 mt-1">24</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-700">Wishlist Items</h3>
                        <p class="text-2xl font-bold text-teal-700 mt-1">8</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-700">Pending Orders</h3>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">2</p>
                    </div>
                </div>

                <!-- Placeholder for Future Content -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <p class="text-gray-600 text-sm">
                        This is your dashboard area. From here you can access <span
                            class="font-medium text-gray-800">orders, wishlist, and account settings.</span>
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>