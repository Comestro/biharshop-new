<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => 'Admin - ' . config('app.name')])
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-100">

    <x-global.loader />

    <!-- Mobile overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-30 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform 
               -translate-x-full transition-transform duration-300 lg:translate-x-0">
        <div class="flex flex-col h-full">

            <!-- Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200 bg-white">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-semibold text-gray-900">
                    Admin
                </a>
                <button id="closeSidebar" class="lg:hidden text-gray-600 hover:text-black text-2xl">
                    &times;
                </button>
            </div>

            <nav class="flex flex-col flex-1 px-4 py-4 space-y-1 overflow-y-auto">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors
                    {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Products -->
                <a href="{{ route('admin.products') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors
                    {{ request()->routeIs('admin.products') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Products</span>
                </a>

                <!-- Categories -->
                <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors
                    {{ request()->routeIs('admin.categories') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>Categories</span>
                </a>

                <!-- Members -->
                <a href="{{ route('admin.members') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors
                    {{ request()->routeIs('admin.members') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Members</span>

                    @if($pendingCount = \App\Models\Membership::where('isPaid', true)->where('isVerified', false)->count())
                        <span class="ml-auto px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded-full font-medium">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <!-- Binary Tree -->
                <a href="{{ route('admin.binary-tree') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors
                    {{ request()->routeIs('admin.binary-tree') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="4" r="2" stroke-width="2" />
                        <circle cx="7" cy="12" r="2" stroke-width="2" />
                        <circle cx="17" cy="12" r="2" stroke-width="2" />
                        <circle cx="4" cy="20" r="2" stroke-width="2" />
                        <circle cx="10" cy="20" r="2" stroke-width="2" />
                        <circle cx="14" cy="20" r="2" stroke-width="2" />
                        <circle cx="20" cy="20" r="2" stroke-width="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6L7 10M12 6L17 10M7 14L4 18M7 14L10 18M17 14L14 18M17 14L20 18" />
                    </svg>
                    <span>Binary Tree</span>
                </a>

                <!-- Manage Positions -->
                <a href="{{ route('admin.manage-positions') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors
                    {{ request()->routeIs('admin.manage-positions') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span>Manage Positions</span>
                </a>

                <!-- Coupons -->
                <a href="{{ route('admin.coupons') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors
                    {{ request()->routeIs('admin.coupons') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span>Coupons</span>
                </a>

                <!-- Plans -->
                <a href="{{ route('admin.manageplans') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors
                    {{ request()->routeIs('admin.manageplans') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Plans</span>
                </a>

                <!-- Withdrawals -->
                <a href="{{ route('admin.withdrawals') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors
                    {{ request()->routeIs('admin.withdrawals') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Withdrawals</span>
                </a>

                <!-- Logout -->
                <div class="mt-auto pt-4 border-t border-gray-200">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 w-full px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>

            </nav>

        </div>
    </div>

    <!-- Main content -->
    <div class="lg:pl-64 flex flex-col min-h-screen">

        <!-- Topbar -->
        <header
            class="bg-white border-b border-gray-200 flex items-center justify-between px-4 py-3 sticky top-0 z-30 shadow-sm">
            <button id="openSidebar" class="text-gray-600 hover:text-black lg:hidden text-2xl">
                &#9776;
            </button>

            <div class="flex items-center gap-2 text-sm text-gray-600">
                <span class="font-medium text-gray-800">Admin</span>
                <span>/</span>
                <span class="capitalize">
                    {{ \Illuminate\Support\Str::of(Route::currentRouteName())->after('admin.') }}
                </span>
            </div>

            <div class="hidden lg:flex items-center gap-3">
                <span class="text-sm text-gray-700">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </header>

        <main class="px-5 py-3">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        openBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
        integrity="sha512-BNa5lI9Bv7YdZjJ2P8cjoMn2PQ4j/wcgUp3NEPoPFcAckU4iigFsMghvYDn8ApX2HFqRSbVSSASzd8u1I6W5mA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>