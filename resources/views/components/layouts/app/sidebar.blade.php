<!DOCTYPE html>
<html class="light">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r hidden lg:block">
            <div class="flex flex-col h-full">
                <div class="h-16 flex items-center px-6 border-b">
                    <a href="/" class="text-2xl font-bold">BiharShop</a>
                </div>
                
                <nav class="flex-1 px-4 py-4 space-y-2">
                    <a href="{{ route('home') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100">
                        Home
                    </a>
                    <a href="{{ route('membership.register') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-100">
                        Become a Member
                    </a>
                </nav>
            </div>
        </div>
        {{ $slot }}

        @fluxScripts
    </body>
</html>
