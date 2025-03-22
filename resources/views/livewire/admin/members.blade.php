<div>
    <div class="py-12 bg-gray-50" x-data="{ showModal: @entangle('showModal') }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                class="block w-full sm:w-auto rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all">All Members</option>
                            <option value="pending">Pending Verification</option>
                            <option value="verified">Verified</option>
                            <option value="unpaid">Unpaid</option>
                        </select>

                        <div class="relative">
                            <input type="search"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="Search members..."
                                   class="block w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10">
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
                                        <button wire:click="showMemberDetails({{ $member->id }})"
                                                class="text-slate-600 hover:text-slate-900">
                                            Quick View
                                        </button>
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

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $members->links() }}
                </div>
            </div>
        </div>

        <!-- Member Details Modal -->
        <div x-show="showModal"
             x-cloak
             class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 sm:p-6"
             x-transition>
            @if($selectedMembership)
            <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                 @click.away="showModal = false">
                <div class="p-6 sm:p-8">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-xl font-semibold">Member Details - {{ $selectedMembership->token }}</h2>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Member Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-medium text-gray-900 mb-3">Personal Information</h3>
                                <div class="space-y-2 text-sm">
                                    <p><span class="text-gray-500">Name:</span> {{ $selectedMembership->name }}</p>
                                    <p><span class="text-gray-500">Email:</span> {{ $selectedMembership->email }}</p>
                                    <p><span class="text-gray-500">Mobile:</span> {{ $selectedMembership->mobile }}</p>
                                    <p><span class="text-gray-500">DOB:</span> {{ $selectedMembership->date_of_birth }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-medium text-gray-900 mb-3">Documents</h3>
                                <div class="space-y-2 text-sm">
                                    <p><span class="text-gray-500">PAN:</span> {{ $selectedMembership->pancard }}</p>
                                    <p><span class="text-gray-500">Aadhar:</span> {{ $selectedMembership->aadhar_card }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-medium text-gray-900 mb-3">Bank Details</h3>
                                <div class="space-y-2 text-sm">
                                    <p><span class="text-gray-500">Bank:</span> {{ $selectedMembership->bank_name }}</p>
                                    <p><span class="text-gray-500">Account:</span> {{ $selectedMembership->account_no }}</p>
                                    <p><span class="text-gray-500">IFSC:</span> {{ $selectedMembership->ifsc }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-medium text-gray-900 mb-3">Address</h3>
                                <div class="space-y-2 text-sm">
                                    <p>{{ $selectedMembership->home_address }}</p>
                                    <p>{{ $selectedMembership->city }}, {{ $selectedMembership->state }} - {{ $selectedMembership->pincode }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($selectedMembership->image)
                    <div class="mt-6">
                        <h3 class="font-medium">Profile Photo</h3>
                        <img src="{{ Storage::url($selectedMembership->image) }}"
                             class="mt-2 h-32 w-32 object-cover rounded-lg">
                    </div>
                    @endif

                    <div class="mt-8 flex justify-end space-x-4">
                        <button @click="showModal = false"
                                class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">
                            Close
                        </button>
                        @if(!$selectedMembership->isVerified && $selectedMembership->isPaid)
                        <button wire:click="approveMember"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Approve Member
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <x-global.loader wire:loading.flex wire:target="approveMember" message="Approving member..." />
    <x-global.loader wire:loading.flex wire:target="showMemberDetails" message="Loading member details..." />
    <x-global.loader wire:loading.flex wire:target="statusFilter" message="Filtering members..." />

</div>
