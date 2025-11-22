<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Panel - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    @livewireStyles
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .sidebar-enter {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-gray-50">
    <x-global.loader />

    <div x-data="{ sidebarOpen: false }" class=" flex">
        <!-- Backdrop for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-30 w-72 transform bg-indigo-600 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

            <!-- Logo Section -->
            <div class="flex items-center justify-center h-20 px-6 bg-indigo-700">
                <div class="text-center">
                    <h1 class="text-white text-2xl font-bold tracking-wide">Member Panel</h1>
                    <p class="text-indigo-200 text-xs mt-1">Welcome back!</p>
                </div>
            </div>

            @php
                $mem = auth()->user()->membership ?? null;
                $parentName = '-';
                $parentToken = '-';
                $parentId = '-';
                if ($mem) {
                    $pos = \App\Models\BinaryTree::where('member_id', $mem->id)->first();
                    $parent = null;
                    if ($pos && $pos->parent_id) {
                        $parent = \App\Models\Membership::find($pos->parent_id);
                    } elseif ($mem->referal_id) {
                        $parent = \App\Models\Membership::find($mem->referal_id);
                    }
                    if ($parent) {
                        $parentName = $parent->name;
                        $parentToken = $parent->membership_id;
                    }
                }
            @endphp
            <div class="px-4 py-3 bg-indigo-50 border-b border-indigo-100">
                <div class="text-indigo-900 font-semibold text-sm">Sponsor (UPLINE)</div>
                <div class="mt-1 text-sm text-indigo-800">{{ $parentName }}</div>
                <div class="mt-0.5 text-xs text-indigo-700">Membership ID: {{ $parentToken }}</div>
            </div>

            <!-- Navigation -->
            <nav class="mt-8 px-4">
                <a href="{{ route('member.dashboard') }}"
                    class="group flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 {{ request()->routeIs('member.dashboard') ? 'bg-indigo-700' : 'hover:bg-indigo-500' }}">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('member.dashboard') ? 'bg-indigo-800' : 'bg-indigo-500' }} group-hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>

                <a href="{{ route('member.wallet') }}"
                    class="group flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 {{ request()->routeIs('member.wallet') ? 'bg-indigo-700' : 'hover:bg-indigo-500' }}">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('member.wallet') ? 'bg-indigo-800' : 'bg-indigo-500' }} group-hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">Wallet</span>
                </a>

                <!-- PROFILE WITH SUBMENU -->
                <div x-data="{ open: {{ request()->routeIs('member.profile') ? 'true' : 'false' }} }">

                    <!-- MAIN PROFILE BUTTON -->
                    <button @click="open = !open"
                        class="w-full group flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200
        {{ request()->routeIs('member.profile') ? 'bg-indigo-700' : 'hover:bg-indigo-500' }}">

                        <!-- Icon -->
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg
            {{ request()->routeIs('member.profile') ? 'bg-indigo-800' : 'bg-indigo-500' }}
            group-hover:bg-indigo-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>

                        <span class="ml-3 font-medium">Profile</span>

                        <!-- Arrow -->
                        <svg class="w-4 h-4 ml-auto transition-transform duration-300" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- SUBMENU INSIDE SIDEBAR -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1" class="ml-14 mt-2 space-y-1">

                        <!-- View Profile -->
                        <a href="{{ route('member.profile', ['tab' => 'view']) }}"
                            class="block px-3 py-2 text-sm rounded-lg transition
            {{ request()->fullUrlIs('*tab=view*') ? 'bg-indigo-100 text-indigo-800' : 'text-indigo-50 hover:bg-indigo-500/40' }}">
                            View Profile
                        </a>

                        <!-- Edit Profile -->
                        <a href="{{ route('member.profile', ['tab' => 'edit']) }}"
                            class="block px-3 py-2 text-sm rounded-lg transition
            {{ request()->fullUrlIs('*tab=edit*') ? 'bg-indigo-100 text-indigo-800' : 'text-indigo-50 hover:bg-indigo-500/40' }}">
                            Edit Profile
                        </a>

                        <!-- Change Password -->
                        <a href="{{ route('member.profile', ['tab' => 'password']) }}"
                            class="block px-3 py-2 text-sm rounded-lg transition
            {{ request()->fullUrlIs('*tab=password*') ? 'bg-indigo-100 text-indigo-800' : 'text-indigo-50 hover:bg-indigo-500/40' }}">
                            Change Password
                        </a>
                    </div>
                </div>


                <a href="{{ route('member.tree') }}"
                    class="group flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 {{ request()->routeIs('member.tree') ? 'bg-indigo-700' : 'hover:bg-indigo-500' }}">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('member.tree') ? 'bg-indigo-800' : 'bg-indigo-500' }} group-hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">My Tree</span>
                </a>

                <a href="{{ route('member.referrals') }}"
                    class="group flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 {{ request()->routeIs('member.referrals') ? 'bg-indigo-700' : 'hover:bg-indigo-500' }}">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('member.referrals') ? 'bg-indigo-800' : 'bg-indigo-500' }} group-hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">My Referrals</span>
                </a>

                @php $myToken = auth()->user()->membership->token ?? null; @endphp
                @if ($myToken)
                    <a href="{{ route('member.welcome-letter') }}"
                        class="group flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 {{ request()->routeIs('member.welcome-letter') ? 'bg-indigo-700' : 'hover:bg-indigo-500' }}">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('member.welcome-letter') ? 'bg-indigo-800' : 'bg-indigo-500' }} group-hover:bg-indigo-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7h18M3 7l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="ml-3 font-medium">Welcome Letter</span>
                    </a>
                @endif

                <a href="{{ route('member.income-report') }}"
                    class="group flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 {{ request()->routeIs('member.income-report') ? 'bg-indigo-700' : 'hover:bg-indigo-500' }}">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('member.income-report') ? 'bg-indigo-800' : 'bg-indigo-500' }} group-hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">Income Report</span>
                </a>

                <a href="{{ route('member.epins') }}"
                    class="group flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 {{ request()->routeIs('member.epins') ? 'bg-indigo-700' : 'hover:bg-indigo-500' }}">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('member.epins') ? 'bg-indigo-800' : 'bg-indigo-500' }} group-hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v8m-4-4h8M5 3a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">E-PINs</span>
                </a>

                <a href="{{ route('member.id-card') }}"
                    class="group flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 {{ request()->routeIs('member.id-card') ? 'bg-indigo-700' : 'hover:bg-indigo-500' }}">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('member.id-card') ? 'bg-indigo-800' : 'bg-indigo-500' }} group-hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 11-4 0 2 2 0 014 0zM12 14l-3 3m0 0l-3-3m3 3V4m6 13l3-3m0 0l3 3m-3-3V4" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">ID Card</span>
                </a>

                <a href="{{ route('member.kyc-documents') }}"
                    class="group flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 {{ request()->routeIs('member.kyc-documents') ? 'bg-indigo-700' : 'hover:bg-indigo-500' }}">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('member.kyc-documents') ? 'bg-indigo-800' : 'bg-indigo-500' }} group-hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h10M7 16h10M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">KYC Documents</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class=" px-4 w-full">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full group flex items-center px-4 py-3 text-white rounded-lg hover:bg-indigo-500 transition-all duration-200">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-500 group-hover:bg-indigo-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span class="ml-3 font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Top Navigation Bar -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
                <div class="flex justify-between items-center px-6 py-4">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 md:p-6 overflow-y-auto">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <p class="text-center text-sm text-gray-500">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </footer>
        </div>
    </div>

    @livewireScripts
</body>

</html>
