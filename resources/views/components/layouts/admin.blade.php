<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - {{ config('app.name') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <x-global.loader />
    <div class="min-h-screen bg-gray-100 text-black">
        <x-layouts.admin.sidebar />

        <div class="lg:pl-64">
            <main class="py-3">
                <div class="p-4">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
