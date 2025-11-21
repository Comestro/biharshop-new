<div>
    <!-- Success Message -->
    @if(session()->has('message'))
        <div class="mb-6 mx-4 sm:mx-0 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600"></i>
            {{ session('message') }}
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        
        <!-- Header: Filters + Search -->
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                
                <!-- Status Filter -->
                <div class="flex items-center gap-3">
                    <i class="fas fa-filter text-gray-500"></i>
                    <select wire:model.live="statusFilter"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="all">All Members</option>
                        <option value="pending">Pending Verification</option>
                        <option value="verified">Verified Only</option>
                        <option value="unpaid">Unpaid Members</option>
                    </select>
                </div>

                <!-- Search Box -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="search"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search by name, token, email or mobile..."
                           class="pl-10 pr-4 py-2.5 w-full lg:w-80 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>
        </div>

        <!-- Members Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Member ID
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Name & Sponsor
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Joined
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Member ID / Token -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono font-semibold text-blue-700">
                                    {{ $member->membership_id }}
                                </div>
                            </td>

                            <!-- Name + Sponsor -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $member->name }}
                                </div>
                                @if($member->sponsor)
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-user-tie text-xs"></i>
                                        Sponsor: {{ $member->sponsor->name }} ({{ $member->sponsor->token }})
                                    </div>
                                @endif
                            </td>

                            <!-- Contact -->
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                    {{ $member->email }}
                                </div>
                                <div class="flex items-center gap-1 mt-1">
                                    <i class="fas fa-phone text-gray-400"></i>
                                    {{ $member->mobile }}
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->isVerified)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Verified
                                    </span>
                                @elseif($member->isPaid)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-hourglass-half mr-1"></i> Pending Verify
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                        <i class="fas fa-times mr-1"></i> Unpaid
                                    </span>
                                @endif
                            </td>

                            <!-- Joined Date -->
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $member->created_at->format('d M Y') }}
                                <div class="text-xs text-gray-400">
                                    {{ $member->created_at->format('h:i A') }}
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.members.view', $member->id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                        <i class="fas fa-eye"></i> View
                                    </a>

                                    @if(!$member->isVerified && $member->isPaid)
                                        <button wire:click="approveMember({{ $member->id }})"
                                                wire:confirm="Verify this member and activate their account?"
                                                class="text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                                            <i class="fas fa-check-circle"></i> Verify
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg">No members found</p>
                                <p class="text-sm mt-1">Try adjusting your filters or search term.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination (if you have it) -->
        @if(method_exists($members, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $members->links() }}
            </div>
        @endif
    </div>

    <!-- Loading Overlays -->
    <x-global.loader wire:loading.flex wire:target="statusFilter, search" 
                     message="Updating members list..." />
    <x-global.loader wire:loading.flex wire:target="approveMember" 
                     message="Verifying member..." class="z-50" />
</div>