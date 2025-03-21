<div class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r hidden lg:block">
    <div class="flex flex-col h-full">
        <div class="h-16 flex items-center px-6 border-b">
            <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold">Admin Panel</a>
        </div>
        
        <nav class="flex flex-col flex-1 px-4 py-4">
            <div class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="block px-4 py-2 rounded-lg hover:bg-teal-100 {{ request()->routeIs('admin.dashboard') ? 'bg-teal-100' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </div>
                </a>

                <a href="{{ route('admin.members') }}" 
                   class="block px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.members') ? 'bg-gray-100' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Members
                        @if($pendingCount = \App\Models\Membership::where('isPaid', true)->where('isVerified', false)->count())
                            <span class="ml-2 px-2 py-0.5 text-xs bg-red-100 text-teal-600 rounded-full">{{ $pendingCount }}</span>
                        @endif
                    </div>
                </a>

                <a href="{{ route('admin.binary-tree') }}" 
                   class="block px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.binary-tree') ? 'bg-gray-100' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        Binary Tree
                    </div>
                </a>

                <a href="{{ route('admin.manage-positions') }}" 
                   class="block px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.manage-positions') ? 'bg-gray-100' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Manage Positions
                    </div>
                </a>
            </div>

            <!-- Push logout to bottom -->
            <div class="mt-auto pt-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg text-red-600 hover:bg-red-50">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </div>
                    </button>
                </form>
            </div>
        </nav>
    </div>
</div>
