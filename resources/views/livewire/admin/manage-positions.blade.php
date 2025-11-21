<div x-data="{ showPositionModal: false }">
    <!-- Success / Error Messages -->
    @if(session('message'))
        <div class="mb-6 mx-4 sm:mx-0 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600"></i>
            {{ session('message') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 mx-4 sm:mx-0 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-red-600"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <i class="fas fa-sitemap text-blue-600"></i>
                        Manage Member Positions
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Change sponsor or leg position for any member</p>
                </div>

                <!-- Search -->
                <div class="relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input 
                        type="search" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Search by name, token, email..."
                        class="pl-10 pr-4 py-2.5 w-full lg:w-80 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    />
                </div>
            </div>
        </div>

        <!-- Members Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Member ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Member Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Current Position</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-semibold text-blue-700">{{ $member->token }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                <div class="text-xs text-gray-500">{{ $member->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($position = $member->binaryPosition)
                                    <span class="inline-flex items-center gap-2">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $position->position === 'left' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ ucfirst($position->position) }} Leg
                                        </span>
                                        of
                                        <span class="font-medium text-gray-700">{{ $position->parent->name }}</span>
                                        ({{ $position->parent->token }})
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">Not placed in binary tree</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex items-center gap-4">
                                    <button 
                                        wire:click="selectMember({{ $member->id }})" 
                                        @click="showPositionModal = true"
                                        class="text-blue-600 hover:text-blue-800 flex items-center gap-1.5 font-medium">
                                        <i class="fas fa-exchange-alt"></i> Change Position
                                    </button>

                                    <a href="{{ route('admin.binary-tree') }}?root={{ $member->id }}" 
                                       class="text-green-600 hover:text-green-800 flex items-center gap-1.5 font-medium"
                                       target="_blank">
                                        <i class="fas fa-project-diagram"></i> View Tree
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4 block"></i>
                                <p class="text-lg font-medium">No members found</p>
                                <p class="text-sm mt-1">Try adjusting your search term.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($members->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $members->links() }}
            </div>
        @endif
    </div>

    <!-- Change Position Modal -->
    <div 
        x-show="showPositionModal" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
        x-transition
        @keydown.escape.window="showPositionModal = false"
    >
        @if($selectedMember)
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden" @click.away="showPositionModal = false; $wire.cancelEdit()">
                <!-- Modal Header -->
                <div class="px-6 py-5 bg-blue-600 text-white">
                    <h3 class="text-lg font-semibold flex items-center gap-3">
                        <i class="fas fa-sitemap"></i>
                        Change Position: {{ $selectedMember->name }}
                    </h3>
                    <p class="text-blue-100 text-sm mt-1">
                        Token: <span class="font-mono">{{ $selectedMember->token }}</span>
                    </p>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="updatePosition" class="p-6 space-y-5">
                    @if($selectedMember->binaryPosition)
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">Current Placement:</p>
                            <p class="mt-1 text-sm">
                                <span class="font-medium">{{ ucfirst($selectedMember->binaryPosition->position) }} leg</span>
                                under
                                <span class="font-medium text-blue-700">{{ $selectedMember->binaryPosition->parent->name }}</span>
                                ({{ $selectedMember->binaryPosition->parent->token }})
                            </p>
                        </div>
                    @endif

                    <!-- Sponsor ID Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1"></i> New Sponsor Token/ID
                        </label>
                        <input 
                            type="text" 
                            wire:model="sponsorId"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="e.g. ABC123456"
                            autofocus
                        >
                        @error('sponsorId')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-directions mr-1"></i> Placement Leg
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50 transition-all">
                                <input type="radio" wire:model="position" value="left" class="text-purple-600 focus:ring-purple-500">
                                <span class="ml-3 font-medium">Left Leg</span>
                            </label>
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-400 has-[:checked]:border-orange-600 has-[:checked]:bg-orange-50 transition-all">
                                <input type="radio" wire:model="position" value="right" class="text-orange-600 focus:ring-orange-500">
                                <span class="ml-3 font-medium">Right Leg</span>
                            </label>
                        </div>
                        @error('position')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button 
                            type="button" 
                            @click="showPositionModal = false; $wire.cancelEdit()"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors flex items-center gap-2"
                        >
                            <i class="fas fa-save"></i>
                            Update Position
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- Loading Overlays -->
    <x-global.loader wire:loading.flex wire:target="search" message="Searching members..." />
    <x-global.loader wire:loading.flex wire:target="selectMember" message="Loading member details..." />
    <x-global.loader wire:loading.flex wire:target="updatePosition" message="Updating position..." class="z-50" />
</div>