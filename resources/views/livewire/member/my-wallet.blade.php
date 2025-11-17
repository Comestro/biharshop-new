<div class="bg-white border border-gray-200 rounded-lg p-6">
        @if($kycComplete)
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Commission Summary</h2>
        <p class="text-sm text-gray-600 mt-1">Track your earnings and manage withdrawals</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Total Commission</p>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-green-700">₹{{ number_format($availableBalance, 2) }}</h3>
            <p class="text-xs text-green-600 mt-1">Available for withdrawal</p>
        </div>

        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Wallet Status</p>
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold {{ $isWalletLocked ? 'text-green-700' : 'text-red-600' }}">
                {{ $isWalletLocked ? 'Unlocked' : 'Locked' }}
            </h3>
            <p class="text-xs {{ $isWalletLocked ? 'text-green-600' : 'text-red-600' }} mt-1">
                {{ $isWalletLocked ? 'Account is active' : 'Account inactive' }}
            </p>
        </div>

        <div class="bg-teal-50 border border-teal-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Total Earnings</p>
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-teal-700">₹{{ number_format($totalEarnings, 2) }}</h3>
            <p class="text-xs text-teal-600 mt-1">Binary + Referral + Daily</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Total Entries</p>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-yellow-700">{{ count($commissionHistory) }}</h3>
            <p class="text-xs text-yellow-600 mt-1">Commission records</p>
        </div>
    </div>

    <!-- Commission History Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <div class="flex gap-2">
                <button wire:click="$set('activeCommissionTab','binary')"
                    class="px-6 py-3 text-sm font-semibold border-b-2 transition-colors
                    {{ $activeCommissionTab === 'binary' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                    Binary Commission
                </button>
                <button wire:click="$set('activeCommissionTab','referral')"
                    class="px-6 py-3 text-sm font-semibold border-b-2 transition-colors
                    {{ $activeCommissionTab === 'referral' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                    Referral Commission
                </button>
                <button wire:click="$set('activeCommissionTab','daily')"
                    class="px-6 py-3 text-sm font-semibold border-b-2 transition-colors
                    {{ $activeCommissionTab === 'daily' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                    Daily Commission
                </button>
            </div>
        </div>
    </div>

    <!-- Commission Tables -->
    <div class="overflow-x-auto mb-8">
        @if ($activeCommissionTab === 'binary')
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">Binary Commission History</h3>
                    <span class="text-sm text-gray-600">Total: ₹{{ number_format($binaryCommissionTotal, 2) }}</span>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-x-auto w-full">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Level</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Left Member</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Right Member</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Percentage</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Commission</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($binaryTx as $tx)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ data_get($tx['meta'],'level','-') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if (($tx['status'] ?? '') === 'confirmed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Paid
                                            </span>
                                        @elseif (($tx['status'] ?? '') === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @else
                                            <span class="text-gray-500">{{ $tx['status'] ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-blue-600">{{ data_get($tx['meta'],'left_member','N/A') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-purple-600">{{ data_get($tx['meta'],'right_member','N/A') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ data_get($tx['meta'],'percentage','-') }}%</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-green-700">
                                        ₹{{ number_format(($tx['amount'] ?? 0), 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($tx['created_at'])->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        No commission records yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($activeCommissionTab === 'referral')
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">Referral Commission History</h3>
                    <span class="text-sm text-gray-600">Total: ₹{{ number_format($referralCommissionTotal, 2) }}</span>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Level</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Child Token</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Commission %</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Commission</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($referralComissionHistory as $row)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $row['level'] }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-indigo-600">{{ $row['child_id'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $row['percentage'] }}%</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-green-700">
                                        ₹{{ number_format($row['commission'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        No referral commission yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($activeCommissionTab === 'daily')
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">Daily Commission History</h3>
                    <span class="text-sm text-gray-600">Total: ₹{{ number_format($dailyCommissionTotal, 2) }}</span>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($dailyCommissionTx as $tx)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-semibold text-green-700">₹{{ number_format($tx['amount'], 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $tx['status'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($tx['created_at'])->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        No daily commission yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    
    <!-- Withdrawal Section -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Withdrawal Requests</h3>
        @if($kycComplete)
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 mb-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="flex-1 w-full sm:w-auto">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Amount</label>
                        <input type="number" step="0.01" wire:model="withdrawAmount" 
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500" 
                            placeholder="Enter amount">
                        @if($withdrawPreview)
                        <div class="mt-3 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg p-3">
                            <div class="flex justify-between">
                                <span>Service Charge (5%)</span>
                                <span class="font-semibold">₹{{ number_format($withdrawPreview['service_charge'], 2) }}</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span>TDS (2%)</span>
                                <span class="font-semibold">₹{{ number_format($withdrawPreview['tds'], 2) }}</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span>Net Amount</span>
                                <span class="font-semibold text-green-700">₹{{ number_format($withdrawPreview['net_amount'], 2) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <button wire:click="withdraw" 
                        class="w-full sm:w-auto mt-6 px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                        Request Withdrawal
                    </button>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($withdrawals as $w)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm">
                                    <span class="font-semibold text-red-700">₹{{ number_format($w['amount'], 2) }}</span>
                                    <span class="block text-xs text-gray-500 mt-0.5">
                                        Service: ₹{{ number_format(($w['details']['service_charge'] ?? round($w['amount'] * 0.05, 2)), 2) }} | 
                                        TDS: ₹{{ number_format(($w['details']['tds'] ?? round($w['amount'] * 0.02, 2)), 2) }} | 
                                        Net: ₹{{ number_format(($w['details']['net_amount'] ?? round($w['amount'] * 0.93, 2)), 2) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $w['status'] === 'approved' ? 'bg-green-100 text-green-800' : ($w['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($w['status']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($w['created_at'])->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    No withdrawal requests yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-yellow-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <p class="text-sm font-medium text-yellow-800">Complete your KYC to request withdrawals</p>
            </div>
        @endif
    </div>

    @else
    <div class="text-center py-12">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Verification Required</h2>
        <p class="text-gray-600 max-w-md mx-auto">
            Your membership needs to be verified to access wallet features. Once verified, you'll be able to view commission earnings, transaction history, and request withdrawals.
        </p>
        <div class="mt-6">
            <a href="{{ route('membership.register') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                Complete Verification
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
    @endif
</div>
