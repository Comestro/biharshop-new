<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('message'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('message') }}</div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">Manage Member Positions</h2>
                <input type="search" wire:model.live.debounce.300ms="search" 
                       placeholder="Search members..." 
                       class="rounded-md border-gray-300">
            </div>

            <!-- Member List -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($members as $member)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $member->token }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $member->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($position = $member->binaryPosition)
                                    {{ ucfirst($position->position) }} of {{ $position->parent->name }}
                                @else
                                    No position
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <button wire:click="selectMember({{ $member->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900">
                                    Change Position
                                </button>
                                <button wire:click="viewTree({{ $member->id }})"
                                        class="text-green-600 hover:text-green-900">
                                    View Tree
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $members->links() }}
            </div>
        </div>

        <!-- Change Position Modal -->
        @if($selectedMember)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-lg w-full p-6">
                <h3 class="text-lg font-medium mb-4">Change Position for {{ $selectedMember->name }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Sponsor ID</label>
                        <input type="text" wire:model="sponsorId" class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Position</label>
                        <select wire:model="position" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">Select Position</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="$set('selectedMember', null)" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md">
                            Cancel
                        </button>
                        <button wire:click="updatePosition" 
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                            Update Position
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- View Tree Modal -->
        @if($viewingMember)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Tree for {{ $viewingMember->name }}</h3>
                    <button wire:click="$set('viewingMember', null)" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="h-96 overflow-auto">
                    <livewire:admin.binary-tree :root_id="$viewingMember->id" :key="$viewingMember->id" />
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
