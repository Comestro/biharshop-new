<div class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 hidden lg:block">
    <div class="flex flex-col h-full">
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-semibold text-gray-800">
                Admin Panel
            </a>
        </div>

        <nav class="flex flex-col flex-1 px-4 py-4">
            <div class="space-y-1">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 font-medium' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <!-- Products -->
                <a href="{{ route('admin.products') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.products') ? 'bg-gray-100 font-medium' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Products
                </a>

                <!-- Categories -->
                <a href="{{ route('admin.categories') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.categories') ? 'bg-gray-100 font-medium' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Categories
                </a>

                <!-- Members -->
                <a href="{{ route('admin.members') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.members') ? 'bg-gray-100 font-medium' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Members

                    @if($pendingCount = \App\Models\Membership::where('isPaid', true)->where('isVerified', false)->count())
                        <span class="ml-2 px-2 py-0.5 text-xs bg-red-200 text-red-700 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <!-- Binary Tree -->
                <a href="{{ route('admin.binary-tree') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.binary-tree') ? 'bg-gray-100 font-medium' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                    Binary Tree
                </a>

                <!-- Manage Positions -->
                <a href="{{ route('admin.manage-positions') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.manage-positions') ? 'bg-gray-100 font-medium' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Manage Positions
                </a>

                <!-- Withdrawals -->
                <a href="{{ route('admin.withdrawals') }}"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.withdrawals') ? 'bg-gray-100 font-medium' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v10m8 2a9 9 0 11-16 0 9 9 0 0116 0z"/>
                    </svg>
                    Withdrawals
                </a>
            </div>

            <!-- Logout -->
            <div class="mt-auto pt-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center w-full px-4 py-2 text-left text-red-600 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </div>
</div>
