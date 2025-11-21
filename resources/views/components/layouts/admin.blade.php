<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => 'Admin - ' . config('app.name')])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @livewireStyles
    <style>
        .sidebar-link:hover { @apply bg-gray-50 text-blue-600; }
        .active-link { @apply bg-blue-600 text-white font-medium shadow-sm; }
        .group-header { @apply text-xs font-bold uppercase tracking-wider text-gray-500 px-4 py-3; }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-800">

    <x-global.loader />

    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 overflow-y-auto">

        <div class="flex flex-col h-full">

            <!-- Logo Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200 bg-white">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-11 h-11 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-lg">
                        MLM
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-900">Admin Panel</div>
                        <div class="text-xs text-gray-500 -mt-1">v2.0</div>
                    </div>
                </a>
                <button id="closeSidebar" class="lg:hidden text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 py-6 px-4 space-y-6">

                <!-- MLM Work Group -->
                <div>
                    <div class="group-header flex items-center gap-2">
                        <i class="fas fa-network-wired text-blue-600"></i>
                        MLM WORK
                    </div>

                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link flex items-center gap-4 px-5 py-3.5 rounded-xl mx-3 transition-all {{ request()->routeIs('admin.dashboard') ? 'active-link' : 'text-gray-700' }}">
                        <i class="fas fa-tachometer-alt w-5 text-lg"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.members') }}"
                        class="sidebar-link flex items-center justify-between gap-4 px-5 py-3.5 rounded-xl mx-3 transition-all {{ request()->routeIs('admin.members*') ? 'active-link' : 'text-gray-700' }}">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-users w-5 text-lg"></i>
                            <span class="font-medium">Members</span>
                        </div>
                        @if($pending = \App\Models\Membership::where('isPaid', true)->where('isVerified', false)->count())
                            <span class="px-2.5 py-1 text-xs font-bold text-white bg-red-500 rounded-full shadow">{{ $pending }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.binary-tree') }}"
                        class="sidebar-link flex items-center gap-4 px-5 py-3.5 rounded-xl mx-3 transition-all {{ request()->routeIs('admin.binary-tree') ? 'active-link' : 'text-gray-700' }}">
                        <i class="fas fa-project-diagram w-5 text-lg"></i>
                        <span class="font-medium">Binary Tree View</span>
                    </a>

                    <a href="{{ route('admin.manage-positions') }}"
                        class="sidebar-link flex items-center gap-4 px-5 py-3.5 rounded-xl mx-3 transition-all {{ request()->routeIs('admin.manage-positions') ? 'active-link' : 'text-gray-700' }}">
                        <i class="fas fa-sitemap w-5 text-lg"></i>
                        <span class="font-medium">Manage Positions</span>
                    </a>

                    <a href="{{ route('admin.withdrawals') }}"
                        class="sidebar-link flex items-center gap-4 px-5 py-3.5 rounded-xl mx-3 transition-all {{ request()->routeIs('admin.withdrawals') ? 'active-link' : 'text-gray-700' }}">
                        <i class="fas fa-money-check-alt w-5 text-lg"></i>
                        <span class="font-medium">Withdrawals</span>
                    </a>

                    <a href="{{ route('admin.epins') }}"
                        class="sidebar-link flex items-center gap-4 px-5 py-3.5 rounded-xl mx-3 transition-all {{ request()->routeIs('admin.epins') ? 'active-link' : 'text-gray-700' }}">
                        <i class="fas fa-key w-5 text-lg"></i>
                        <span class="font-medium">e-Pins Management</span>
                    </a>
                    <a href="{{ route('admin.manageplans') }}"
                        class="sidebar-link flex items-center gap-4 px-5 py-3.5 rounded-xl mx-3 transition-all {{ request()->routeIs('admin.manageplans') ? 'active-link' : 'text-gray-700' }}">
                        <i class="fas fa-layer-group w-5 text-lg"></i>
                        <span class="font-medium">Membership Plans</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="mx-6 border-t border-gray-200"></div>

                <!-- E-Commerce Work Group -->
                <div>
                    <div class="group-header flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-emerald-600"></i>
                        E-COMMERCE WORK
                    </div>

                    <a href="{{ route('admin.products') }}"
                        class="sidebar-link flex items-center gap-4 px-5 py-3.5 rounded-xl mx-3 transition-all {{ request()->routeIs('admin.products*') ? 'active-link' : 'text-gray-700' }}">
                        <i class="fas fa-box-open w-5 text-lg"></i>
                        <span class="font-medium">Products</span>
                    </a>

                    <a href="{{ route('admin.categories') }}"
                        class="sidebar-link flex items-center gap-4 px-5 py-3.5 rounded-xl mx-3 transition-all {{ request()->routeIs('admin.categories') ? 'active-link' : 'text-gray-700' }}">
                        <i class="fas fa-tags w-5 text-lg"></i>
                        <span class="font-medium">Categories</span>
                    </a>

                    <a href="{{ route('admin.coupons') }}"
                        class="sidebar-link flex items-center gap-4 px-5 py-3.5 rounded-xl mx-3 transition-all {{ request()->routeIs('admin.coupons') ? 'active-link' : 'text-gray-700' }}">
                        <i class="fas fa-ticket-alt w-5 text-lg"></i>
                        <span class="font-medium">Coupons & Offers</span>
                    </a>

                    
                </div>

            </nav>

            <!-- Logout -->
            <div class="border-t border-gray-200 p-5 bg-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-3 px-5 py-3.5 text-red-600 hover:bg-red-50 rounded-xl font-medium transition-all hover:shadow-md">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="lg:pl-72 min-h-screen flex flex-col">

        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">

                <!-- Mobile Menu Button -->
                <button id="openSidebar" class="lg:hidden text-gray-600 hover:text-gray-900 text-2xl">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-500">Admin</span>
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <span class="font-semibold text-gray-900 capitalize">
                        {{ \Illuminate\Support\Str::of(Route::currentRouteName())->after('admin.')->replace(['-', '_'], ' ')->title() }}
                    </span>
                </div>

                <!-- User Info -->
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Super Administrator</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6 lg:p-10 bg-gray-50">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 px-6 py-5 text-center text-xs text-gray-500">
            © {{ date('Y') }} {{ config('app.name') }} • MLM + E-Commerce Admin Panel • Powered by Laravel
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

        // Auto-close mobile menu on route change (Livewire)
        document.addEventListener('livewire:load', () => {
            Livewire.hook('message.processed', () => {
                if (window.innerWidth < 1024) closeSidebar();
            });
        });
    </script>
</body>
</html>