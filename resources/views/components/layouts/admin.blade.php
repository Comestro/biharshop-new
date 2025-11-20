<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => 'Admin - ' . config('app.name')])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @livewireStyles
    <style>
        .active-link {
            @apply bg-blue-600 text-white font-medium;
        }
        .sidebar-link:hover {
            @apply bg-gray-50 text-blue-600;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-800">

    <x-global.loader />

    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0">

        <div class="flex flex-col h-full">

            <!-- Logo / Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                        MLM
                    </div>
                    <span class="text-xl font-bold text-gray-900">Admin Panel</span>
                </a>
                <button id="closeSidebar" class="lg:hidden text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'active-link' : 'text-gray-700' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.members') }}"
                    class="sidebar-link flex items-center justify-between gap-4 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.members') ? 'active-link' : 'text-gray-700' }}">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-users w-5"></i>
                        <span>Members</span>
                    </div>
                    @if($pending = \App\Models\Membership::where('isPaid', true)->where('isVerified', false)->count())
                        <span class="px-2 py-0.5 text-xs font-semibold text-white bg-red-500 rounded-full">{{ $pending }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.binary-tree') }}"
                    class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.binary-tree') ? 'active-link' : 'text-gray-700' }}">
                    <i class="fas fa-project-diagram w-5"></i>
                    <span>Binary Tree View</span>
                </a>

                <a href="{{ route('admin.manage-positions') }}"
                    class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.manage-positions') ? 'active-link' : 'text-gray-700' }}">
                    <i class="fas fa-sitemap w-5"></i>
                    <span>Manage Positions</span>
                </a>

                <a href="{{ route('admin.products') }}"
                    class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.products') ? 'active-link' : 'text-gray-700' }}">
                    <i class="fas fa-box-open w-5"></i>
                    <span>Products</span>
                </a>

                <a href="{{ route('admin.categories') }}"
                    class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.categories') ? 'active-link' : 'text-gray-700' }}">
                    <i class="fas fa-tags w-5"></i>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.manageplans') }}"
                    class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.manageplans') ? 'active-link' : 'text-gray-700' }}">
                    <i class="fas fa-layer-group w-5"></i>
                    <span>Membership Plans</span>
                </a>

                <a href="{{ route('admin.epins') }}"
                    class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.epins') ? 'active-link' : 'text-gray-700' }}">
                    <i class="fas fa-key w-5"></i>
                    <span>e-Pins</span>
                </a>

                <a href="{{ route('admin.coupons') }}"
                    class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.coupons') ? 'active-link' : 'text-gray-700' }}">
                    <i class="fas fa-ticket-alt w-5"></i>
                    <span>Coupons</span>
                </a>

                <a href="{{ route('admin.withdrawals') }}"
                    class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.withdrawals') ? 'active-link' : 'text-gray-700' }}">
                    <i class="fas fa-money-check-alt w-5"></i>
                    <span>Withdrawals</span>
                </a>

            </nav>

            <!-- Logout -->
            <div class="border-t border-gray-200 p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-4 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="lg:pl-72 min-h-screen flex flex-col">

        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-6 py-4">

                <!-- Mobile Menu Button -->
                <button id="openSidebar" class="lg:hidden text-gray-600 hover:text-gray-900 text-2xl">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="font-semibold text-gray-900">Admin</span>
                    <span>/</span>
                    <span class="capitalize font-medium">
                        {{ \Illuminate\Support\Str::of(Route::currentRouteName())->after('admin.')->replace('-', ' ') }}
                    </span>
                </div>

                <!-- User Info -->
                <div class="flex items-center gap-4">
                    <span class="hidden sm:block text-sm font-medium text-gray-700">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </span>
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6 lg:p-8">
            {{ $slot }}
        </main>

        <!-- Footer (Optional) -->
        <footer class="bg-white border-t border-gray-200 px-6 py-4 text-center text-xs text-gray-500">
            © {{ date('Y') }} {{ config('app.name') }} • MLM Admin Panel • All rights reserved.
        </footer>
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

        openBtn?.addEventListener('click', openSidebar);
        closeBtn?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);
    </script>
</body>
</html>