<div class="py-12" x-data="{ showModal: @entangle('showModal') }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Flash Message -->
        @if(session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <!-- Filters and Search -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-4">
                    <select wire:model.live="statusFilter" class="rounded-md border-gray-300">
                        <option value="all">All Members</option>
                        <option value="pending">Pending Verification</option>
                        <option value="verified">Verified</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                </div>
                <div class="flex items-center space-x-4">
                    <input type="search" wire:model.live.debounce.300ms="search" 
                           placeholder="Search members..." 
                           class="rounded-md border-gray-300">
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
                                <button wire:click="showMemberDetails({{ $member->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900">
                                    View Details
                                </button>
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

            <div class="mt-4">
                {{ $members->links() }}
            </div>
        </div>
    </div>

    <!-- Member Details Modal -->
    <div x-show="showModal" 
         x-cloak
         class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4"
         x-transition>
        @if($selectedMembership)
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto"
             @click.away="showModal = false">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-xl font-semibold">Member Details - {{ $selectedMembership->token }}</h2>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Member Information Sections -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium">Personal Information</h3>
                        <p>Name: {{ $selectedMembership->name }}</p>
                        <p>Email: {{ $selectedMembership->email }}</p>
                        <p>Mobile: {{ $selectedMembership->mobile }}</p>
                        <p>DOB: {{ $selectedMembership->date_of_birth }}</p>
                    </div>
                    <div>
                        <h3 class="font-medium">Bank Details</h3>
                        <p>Bank: {{ $selectedMembership->bank_name }}</p>
                        <p>Account: {{ $selectedMembership->account_no }}</p>
                        <p>IFSC: {{ $selectedMembership->ifsc }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <h3 class="font-medium">Address</h3>
                    <p>{{ $selectedMembership->home_address }}</p>
                    <p>{{ $selectedMembership->city }}, {{ $selectedMembership->state }} - {{ $selectedMembership->pincode }}</p>
                </div>

                <div class="mt-4">
                    <h3 class="font-medium">Documents</h3>
                    <p>PAN: {{ $selectedMembership->pancard }}</p>
                    <p>Aadhar: {{ $selectedMembership->aadhar_card }}</p>
                </div>

                @if($selectedMembership->image)
                <div class="mt-4">
                    <h3 class="font-medium">Profile Photo</h3>
                    <img src="{{ Storage::url($selectedMembership->image) }}" 
                         class="mt-2 h-32 w-32 object-cover rounded-lg">
                </div>
                @endif

                <div class="mt-6 flex justify-end space-x-3">
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
