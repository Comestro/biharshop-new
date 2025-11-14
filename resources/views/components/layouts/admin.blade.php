<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => 'Admin - ' . config('app.name')])
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-100">

    <x-global.loader />

    <!-- Mobile overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-30 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white/95 backdrop-blur border-r shadow-lg transform -translate-x-full transition-transform duration-300 lg:translate-x-0">
        <div class="flex flex-col h-full">
            <div class="h-16 flex items-center justify-between px-6 border-b bg-white/80">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-teal-600">Admin</a>
                <!-- Close button on mobile -->
                <button id="closeSidebar" class="lg:hidden text-gray-600 hover:text-gray-900">
                    &times;
                </button>
            </div>

            <nav class="flex flex-col flex-1 px-4 py-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-teal-50 text-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-teal-100 text-teal-800' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.products') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-800 {{ request()->routeIs('admin.products') ? 'bg-gray-100 text-teal-800' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-4M4 7h4M4 7v10a2 2 0 002 2h4m6-6h2"/></svg>
                    <span>Products</span>
                </a>
                <a href="{{ route('admin.categories') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-800 {{ request()->routeIs('admin.categories') ? 'bg-gray-100 text-teal-800' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8M4 18h6"/></svg>
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.members') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-800 {{ request()->routeIs('admin.members') ? 'bg-gray-100 text-teal-800' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857"/></svg>
                    <span>Members</span>
                    @if($pendingCount = \App\Models\Membership::where('isPaid', true)->where('isVerified', false)->count())
                        <span class="ml-auto px-2 py-0.5 text-xs bg-red-100 text-teal-700 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.binary-tree') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-800 {{ request()->routeIs('admin.binary-tree') ? 'bg-gray-100 text-teal-800' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                    <span>Binary Tree</span>
                </a>
                <a href="{{ route('admin.manage-positions') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-800 {{ request()->routeIs('admin.manage-positions') ? 'bg-gray-100 text-teal-800' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6h10M4 6h2m4 6h10M4 12h2m4 6h10M4 18h2"/></svg>
                    <span>Manage Positions</span>
                </a>
                <a href="{{ route('admin.coupons') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-800 {{ request()->routeIs('admin.coupons') ? 'bg-gray-100 text-teal-800' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13a4 4 0 110-8 4 4 0 010 8zm7 7a4 4 0 110-8 4 4 0 010 8z"/></svg>
                    <span>Coupons</span>
                </a>

                <div class="mt-auto pt-4 border-t">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-red-600 hover:bg-red-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4"/></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main content -->
    <div class="lg:pl-64 flex flex-col min-h-screen">

        <!-- Topbar -->
        <header class="bg-white/90 backdrop-blur shadow flex items-center justify-between px-4 py-3 sticky top-0 z-30">
            <button id="openSidebar" class="text-gray-600 hover:text-gray-900 focus:outline-none lg:hidden">
                &#9776;
            </button>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <span class="font-medium text-gray-800">Admin</span>
                <span>/</span>
                <span class="capitalize">{{ \Illuminate\Support\Str::of(Route::currentRouteName())->after('admin.') }}</span>
            </div>
            <div class="hidden lg:flex items-center gap-3">
                <span class="text-sm text-gray-500">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </header>

        <main class="p-4 lg:p-6">
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
