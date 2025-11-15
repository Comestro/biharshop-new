<div>
    <div x-data="{
        showPositionModal: false,
        showTreeModal: false,
        init() {
            window.addEventListener('closeModal', () => {
                this.showPositionModal = false;
                this.showTreeModal = false;
            })
        }
     }">
            @if(session('message'))
                <div class="mb-4 mx-4 sm:mx-0 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 mx-4 sm:mx-0 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                        <h2 class="text-xl font-semibold text-gray-900">Manage Member Positions</h2>
                        <div class="relative">
                            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search members..."
                                class="block w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Member List -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current
                                    Position</th>
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
                                            @click="showPositionModal = true" class="text-indigo-600 hover:text-indigo-900">
                                            Change Position
                                        </button>
                                        <a href="{{ route('admin.members.view',$member->id) }}#tree" class="text-green-600 hover:text-green-900">
                                            View Tree
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $members->links() }}
                </div>
            </div>

            <!-- Change Position Modal -->
            <div x-show="showPositionModal" x-cloak
                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 sm:p-6"
                x-transition>
                @if($selectedMember)
                    <div class="bg-white rounded-xl max-w-lg w-full shadow-xl" @click.away="showPositionModal = false">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Change Position for {{ $selectedMember->name }}
                            </h3>
                            @if($selectedMember->binaryPosition)
                                <p class="mt-1 text-sm text-gray-500">Current sponsor:
                                    {{ $selectedMember->binaryPosition->parent->name }}
                                </p>
                            @endif
                        </div>

                        @if(session('error'))
                            <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form wire:submit.prevent="updatePosition" class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sponsor ID</label>
                                    <input type="text" wire:model="sponsorId"
                                        class="mt-1 block w-full rounded-md border-gray-300"
                                        placeholder="Enter sponsor's member ID">
                                    @error('sponsorId')
                                        <span class="mt-1 text-sm text-red-600 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Position</label>
                                    <select wire:model="position" class="mt-1 block w-full rounded-md border-gray-300">
                                        <option value="">Select Position</option>
                                        <option value="left">Left</option>
                                        <option value="right">Right</option>
                                    </select>
                                    @error('position')
                                        <span class="mt-1 text-sm text-red-600 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                @error('general')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" @click="showPositionModal = false; $wire.cancelEdit()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                    Update Position
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

        </div>

    <x-global.loader wire:loading.flex wire:target="updatePosition" message="Updating position..." />
    <x-global.loader wire:loading.flex wire:target="selectMember" message="Loading member..." />
    <x-global.loader wire:loading.flex wire:target="viewTree" message="Loading tree view..." />

</div>