<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Panel - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>

<body class="bg-gray-50">
    <x-global.loader />

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-30 w-64 transform bg-teal-700 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0 transition ease-in-out duration-300"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">

            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-teal-800">
                <span class="text-white text-2xl font-bold">Member Panel</span>
            </div>

            <!-- Navigation -->
            <nav class="mt-4 space-y-2 px-4">
                <a href="{{ route('member.dashboard') }}"
                    class="flex items-center px-4 py-2 text-white rounded-lg {{ request()->routeIs('member.dashboard') ? 'bg-teal-800' : 'hover:bg-teal-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('member.wallet') }}"
                    class="flex items-center px-4 py-2 text-white rounded-lg {{ request()->routeIs('member.wallet') ? 'bg-teal-800' : 'hover:bg-teal-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Wallet
                </a>

                <a href="{{ route('member.profile') }}"
                    class="flex items-center px-4 py-2 text-white rounded-lg {{ request()->routeIs('member.profile') ? 'bg-indigo-800' : 'hover:bg-indigo-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile
                </a>

                <a href="{{ route('member.tree') }}"
                    class="flex items-center px-4 py-2 text-white rounded-lg {{ request()->routeIs('member.tree') ? 'bg-indigo-800' : 'hover:bg-indigo-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                    My Tree
                </a>

                <a href="{{ route('member.referrals') }}"
                    class="flex items-center px-4 py-2 text-white rounded-lg {{ request()->routeIs('member.referrals') ? 'bg-indigo-800' : 'hover:bg-indigo-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    My Referrals
                </a>
            </nav>

            <!-- Logout -->
            <div class="absolute bottom-0 w-full p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-2 text-white rounded-lg hover:bg-indigo-600">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <div class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-4">
                    <!-- Sidebar Toggle (Mobile) -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Center Info (Member ID) -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Member ID:</span>
                        <span class="text-sm font-semibold text-gray-800">
                            {{ auth()->user()->membership->isVerified ? auth()->user()->membership->token : 'N/A' }}
                        </span>
                    </div>

                    <!-- Wallet Balance (Right) -->
                    <livewire:member.component.total-wallet-balance />
                </div>
            </div>


            <!-- Main Content -->
            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
    @livewireScripts
</body>

</html>