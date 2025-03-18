<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <nav class="space-y-1">
            <a href="{{ route('settings.profile') }}" 
               class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.profile') ? 'bg-gray-100' : '' }}"
               wire:navigate>
                {{ __('Profile') }}
            </a>
            <a href="{{ route('settings.password') }}"
               class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.password') ? 'bg-gray-100' : '' }}"
               wire:navigate>
                {{ __('Password') }}
            </a>
            <a href="{{ route('settings.appearance') }}"
               class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.appearance') ? 'bg-gray-100' : '' }}"
               wire:navigate>
                {{ __('Appearance') }}
            </a>
        </nav>
    </div>

    <hr class="md:hidden border-t border-gray-200 w-full my-4">

    <div class="flex-1 self-stretch max-md:pt-6">
        @if(isset($heading))
            <h1 class="text-2xl font-semibold text-gray-900">{{ $heading }}</h1>
        @endif
        
        @if(isset($subheading))
            <p class="mt-1 text-sm text-gray-600">{{ $subheading }}</p>
        @endif

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
