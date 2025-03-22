@props(['message' => 'Loading...'])

<div wire:loading {{ $attributes }} class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="loading-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-white/80 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="flex flex-col items-center">
            <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="mt-4 text-sm font-medium text-gray-900">{{ $message }}</span>
        </div>
    </div>
</div>
