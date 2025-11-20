<div>
    <!-- Member Detail Card -->
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                @if ($member->image)
                    <img src="{{ Storage::url($member->image) }}" alt="{{ $member->name }}" class="h-16 w-16 rounded-full object-cover border-2 border-gray-200">
                @else
                    <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                        <span class="text-xl font-medium text-gray-500">{{ substr($member->name, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $member->name }}</h2>
                    <div class="text-xs text-gray-500">Member ID: <span class="font-mono text-blue-700">{{ $member->membership_id ?? $member->token }}</span></div>
                    @if($member->sponsor)
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-user-tie text-xs"></i>
                            Sponsor: {{ $member->sponsor->name }} ({{ $member->sponsor->token }})
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
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
                @if(!$member->isVerified && $member->isPaid)
                    <button wire:click="approveMember" class="mt-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <i class="fas fa-check-circle mr-1"></i> Approve Member
                    </button>
                @endif
            </div>
        </div>
        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 flex flex-col items-center">
                <span class="text-sm font-medium text-gray-500">Left Team</span>
                <span class="text-2xl font-bold text-blue-600">{{ $leftTeamSize }}</span>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 flex flex-col items-center">
                <span class="text-sm font-medium text-gray-500">Right Team</span>
                <span class="text-2xl font-bold text-green-600">{{ $rightTeamSize }}</span>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 flex flex-col items-center">
                <span class="text-sm font-medium text-gray-500">Total</span>
                <span class="text-2xl font-bold text-gray-700">{{ $totalTeamSize }}</span>
            </div>
        </div>
    </div>

    <!-- Tabs and Sections -->
    @include('livewire.admin.member-tabs', [
        'member' => $member,
        'activeTab' => $activeTab,
        'transactions' => $transactions,
        'withdrawals' => $withdrawals,
        'walletBalance' => $walletBalance,
        'lockedDaily' => $lockedDaily,
        'availableBalance' => $availableBalance,
        'binaryUplines' => $binaryUplines,
        'referralUplines' => $referralUplines,
        'binaryCommissionTx' => $binaryCommissionTx,
        'binaryCommissionTotal' => $binaryCommissionTotal,
        'referralCommissionTx' => $referralCommissionTx,
        'referralCommissionTotal' => $referralCommissionTotal,
        'dailyCommissionTx' => $dailyCommissionTx,
        'dailyCommissionTotal' => $dailyCommissionTotal,
    ])
</div>
