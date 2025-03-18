<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white">
        <header class="border-b border-zinc-200 bg-zinc-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2" wire:navigate>
                            <x-app-logo />
                        </a>
                    </div>

                    <!-- Navigation -->
                    <div class="hidden lg:flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-200' : 'hover:bg-gray-100' }}"
                           wire:navigate>
                            {{ __('Dashboard') }}
                        </a>
                    </div>

                    <!-- Right Side Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <button class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-lg bg-neutral-200 flex items-center justify-center">
                                    <span class="text-sm font-medium">{{ auth()->user()->initials() }}</span>
                                </div>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                <div class="px-4 py-2 border-b">
                                    <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-600">{{ auth()->user()->email }}</p>
                                </div>
                                
                                <a href="{{ route('settings.profile') }}" 
                                   class="block px-4 py-2 text-sm hover:bg-gray-100"
                                   wire:navigate>
                                    {{ __('Settings') }}
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{ $slot }}

        @livewireScripts
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>
