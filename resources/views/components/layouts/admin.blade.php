<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - {{ config('app.name') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
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
    <div id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r shadow-lg transform -translate-x-full transition-transform duration-300 lg:translate-x-0">
        <div class="flex flex-col h-full">
            <div class="h-16 flex items-center justify-between px-6 border-b">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-teal-600">Admin Panel</a>
                <!-- Close button on mobile -->
                <button id="closeSidebar" class="lg:hidden text-gray-600 hover:text-gray-900">
                    &times;
                </button>
            </div>

            <nav class="flex flex-col flex-1 px-4 py-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-teal-100 {{ request()->routeIs('admin.dashboard') ? 'bg-teal-100' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.products') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.products') ? 'bg-gray-100' : '' }}">
                    Products
                </a>
                <a href="{{ route('admin.categories') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.categories') ? 'bg-gray-100' : '' }}">
                    Categories
                </a>
                <a href="{{ route('admin.members') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.members') ? 'bg-gray-100' : '' }}">
                    Members
                    @if($pendingCount = \App\Models\Membership::where('isPaid', true)->where('isVerified', false)->count())
                        <span
                            class="ml-2 px-2 py-0.5 text-xs bg-red-100 text-teal-600 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.binary-tree') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.binary-tree') ? 'bg-gray-100' : '' }}">
                    Binary Tree
                </a>
                <a href="{{ route('admin.manage-positions') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.manage-positions') ? 'bg-gray-100' : '' }}">
                    Manage Positions
                </a>
                <a href="{{ route('admin.coupons') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.coupons') ? 'bg-gray-100' : '' }}">
                   Coupons
                </a>

                <div class="mt-auto pt-4 border-t">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 rounded-lg text-red-600 hover:bg-red-50">
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main content -->
    <div class="lg:pl-64 flex flex-col min-h-screen">

        <!-- Navbar mobile -->
        <header class="bg-white shadow flex items-center justify-between px-4 py-3 lg:hidden">
            <button id="openSidebar" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                &#9776; <!-- Hamburger icon -->
            </button>
            <div class="text-lg font-semibold text-gray-800">Admin Dashboard</div>
            <div></div>
        </header>

        <main class="p-4">
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
</body>

</html>