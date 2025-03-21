<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Select Your Position</h2>

            @if(session()->has('message'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            <div class="mb-6">
                <h3 class="text-lg font-medium mb-2">Parent Member Details</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p><span class="font-medium">Name:</span> {{ $parentMembership->name }}</p>
                    <p><span class="font-medium">Member ID:</span> {{ $parentMembership->token }}</p>
                </div>
            </div>

            @if(count($availablePositions) > 0)
                <form wire:submit.prevent="selectPosition">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Position</label>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($availablePositions as $pos)
                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none">
                                    <input type="radio" 
                                           wire:model="position" 
                                           value="{{ $pos }}"
                                           class="sr-only" 
                                           aria-labelledby="position-{{ $pos }}">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span id="position-{{ $pos }}" class="block text-sm font-medium text-gray-900">
                                                {{ ucfirst($pos) }} Position
                                            </span>
                                        </span>
                                    </span>
                                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2"
                                          :class="{'border-indigo-500': position === '{{ $pos }}', 'border-transparent': position !== '{{ $pos }}'}">
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('position') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('member.tree') }}" 
                           class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Confirm Position
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                No positions available under this member.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
