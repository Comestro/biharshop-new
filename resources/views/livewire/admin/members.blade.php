<div>
    <div x-data="{ showModal: @entangle('showModal') }">
            @if(session()->has('message'))
            <div class="mb-4 mx-4 sm:mx-0 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('message') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                <!-- Filters and Search -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                        <select wire:model.live="statusFilter"
                                class="block w-full sm:w-auto px-3 py-2 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all">All Members</option>
                            <option value="pending">Pending Verification</option>
                            <option value="verified">Verified</option>
                            <option value="unpaid">Unpaid</option>
                        </select>

                        <div class="relative">
                            <input type="search"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="Search members..."
                                   class="block w-full sm:w-64 rounded-lg px-3 py-2 border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Members Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($members as $member)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $member->token }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $member->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $member->email }}<br>
                                    {{ $member->mobile }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span @class([
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        'bg-green-100 text-green-800' => $member->isVerified,
                                        'bg-yellow-100 text-yellow-800' => !$member->isVerified && $member->isPaid,
                                        'bg-gray-100 text-gray-800' => !$member->isPaid,
                                    ])>
                                        {{ $member->isVerified ? 'Verified' : ($member->isPaid ? 'Pending' : 'Unpaid') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.members.view', $member->id) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            View Details
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No members found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
    <x-global.loader wire:loading.flex wire:target="statusFilter" message="Filtering members..." />
            </div>
        </div>

    <x-global.loader wire:loading.flex wire:target="approveMember" message="Approving member..." />
    <x-global.loader wire:loading.flex wire:target="showMemberDetails" message="Loading member details..." />
    <x-global.loader wire:loading.flex wire:target="statusFilter" message="Filtering members..." />

</div>
