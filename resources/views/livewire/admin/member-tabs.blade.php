<div>
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 mb-4">
        <nav class="flex -mb-px">
            <button type="button" wire:click="setTab('personal')" @class([
                'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                'border-blue-500 text-blue-600' => $activeTab === 'personal',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'personal',
            ])>
                Personal Information
            </button>
            <button type="button" wire:click="setTab('financial')" @class([
                'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                'border-blue-500 text-blue-600' => $activeTab === 'financial',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'financial',
            ])>
                Financial Details
            </button>
            <button type="button" wire:click="setTab('network')" @class([
                'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                'border-blue-500 text-blue-600' => $activeTab === 'network',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'network',
            ])>
                Network Details
            </button>
            <button type="button" wire:click="setTab('wallet')" @class([
                'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                'border-blue-500 text-blue-600' => $activeTab === 'wallet',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'wallet',
            ])>
                Wallet
            </button>
            <button type="button" wire:click="setTab('tree')" @class([
                'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                'border-blue-500 text-blue-600' => $activeTab === 'tree',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'tree',
            ])>
                Tree
            </button>
            <button type="button" wire:click="setTab('binary_commission')" @class([
                'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                'border-blue-500 text-blue-600' => $activeTab === 'binary_commission',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'binary_commission',
            ])>
                Binary Commission
            </button>
            <button type="button" wire:click="setTab('referral_commission')" @class([
                'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                'border-blue-500 text-blue-600' => $activeTab === 'referral_commission',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'referral_commission',
            ])>
                Referral Commission
            </button>
            <button type="button" wire:click="setTab('daily_cashback')" @class([
                'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                'border-blue-500 text-blue-600' => $activeTab === 'daily_cashback',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'daily_cashback',
            ])>
                Daily Commission
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div>
        @if ($activeTab === 'personal')
            @include('livewire.admin.tabs.personal', ['member' => $member])
        @elseif ($activeTab === 'financial')
            @include('livewire.admin.tabs.financial', ['member' => $member])
        @elseif ($activeTab === 'network')
            @include('livewire.admin.tabs.network', ['member' => $member, 'leftTeamSize' => $leftTeamSize, 'rightTeamSize' => $rightTeamSize, 'totalTeamSize' => $totalTeamSize])
        @elseif ($activeTab === 'wallet')
            @include('livewire.admin.tabs.wallet', ['member' => $member, 'transactions' => $transactions, 'withdrawals' => $withdrawals, 'walletBalance' => $walletBalance, 'lockedDaily' => $lockedDaily, 'availableBalance' => $availableBalance])
        @elseif ($activeTab === 'tree')
            @include('livewire.admin.tabs.tree', ['member' => $member, 'binaryUplines' => $binaryUplines, 'referralUplines' => $referralUplines])
        @elseif ($activeTab === 'binary_commission')
            @include('livewire.admin.tabs.binary-commission', ['binaryCommissionTx' => $binaryCommissionTx, 'binaryCommissionTotal' => $binaryCommissionTotal])
        @elseif ($activeTab === 'referral_commission')
            @include('livewire.admin.tabs.referral-commission', ['referralCommissionTx' => $referralCommissionTx, 'referralCommissionTotal' => $referralCommissionTotal])
        @elseif ($activeTab === 'daily_cashback')
            @include('livewire.admin.tabs.daily-commission', ['dailyCommissionTx' => $dailyCommissionTx, 'dailyCommissionTotal' => $dailyCommissionTotal])
        @endif
    </div>
</div>
