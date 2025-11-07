<div>
    <!-- Sidebar -->
    <aside class="hidden md:block w-64 border-r border-gray-100 bg-gray-50 p-5">
        <h2 class="text-lg font-semibold text-gray-800 mb-5">My Account</h2>
        <nav class="flex flex-col space-y-2 text-sm font-medium">
            <a href="{{ route('myDashboard') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
            <a href="{{ route('myOrder') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                <i class="fa-solid fa-box"></i> Orders
            </a>
            <a href="{{ route('myWishlist') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                <i class="fa-solid fa-heart"></i> Wishlist
            </a>
            <a href="{{ route('myAddress') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg bg-teal-700 text-white transition">
                <i class="fa-solid fa-location-dot"></i> Addresses
            </a>
            <a href="{{ route('myProfile') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                <i class="fa-solid fa-user"></i> Profile
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </nav>
    </aside>
</div>