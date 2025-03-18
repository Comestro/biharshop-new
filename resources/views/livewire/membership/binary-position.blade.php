<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Select Your Position</h2>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Enter Sponsor ID (Optional)</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" wire:model.live="referral_code" 
                           class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Enter sponsor's member ID">
                </div>
            </div>

            @if($sponsor)
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">Sponsor: {{ $sponsor->name }}</p>
                    <p class="text-sm text-gray-500">ID: {{ $sponsor->token }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    @foreach(['left', 'right'] as $pos)
                        <button wire:click="$set('position', '{{ $pos }}')"
                                @if(!in_array($pos, $availablePositions)) disabled @endif
                                class="p-4 border rounded-lg text-center {{ $position === $pos ? 'border-indigo-500 bg-indigo-50' : '' }} 
                                       {{ !in_array($pos, $availablePositions) ? 'opacity-50 cursor-not-allowed' : 'hover:border-indigo-500' }}">
                            <span class="block text-lg font-medium mb-1">{{ ucfirst($pos) }} Position</span>
                            <span class="text-sm text-gray-500">
                                {{ in_array($pos, $availablePositions) ? 'Available' : 'Taken' }}
                            </span>
                        </button>
                    @endforeach
                </div>

                <button wire:click="selectPosition"
                        @if(!$position) disabled @endif
                        class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               {{ !$position ? 'opacity-50 cursor-not-allowed' : '' }}">
                    Confirm Position
                </button>
            @else
                <div class="text-center text-gray-500">
                    No sponsor available at the moment
                </div>
            @endif
        </div>
    </div>
</div>
