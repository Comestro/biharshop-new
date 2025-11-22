<div class="space-y-6">
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-gray-900">Wallet Summary</h3>
            
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-md border border-gray-200 bg-white">
                <p class="text-xs text-gray-500 uppercase">Balance</p>
                <h3 class="text-2xl font-bold text-teal-700">₹{{ number_format($walletBalance, 2) }}</h3>
            </div>
            <div class="p-4 rounded-md border border-gray-200 bg-white">
                <p class="text-xs text-gray-500 uppercase">Locked Daily</p>
                <h3 class="text-2xl font-bold text-amber-700">₹{{ number_format($lockedDaily, 2) }}</h3>
            </div>
            <div class="p-4 rounded-md border border-gray-200 bg-white">
                <p class="text-xs text-gray-500 uppercase">Available To Withdraw</p>
                <h3 class="text-2xl font-bold text-emerald-700">₹{{ number_format($availableBalance, 2) }}</h3>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="p-4 rounded-md border border-gray-200 bg-white">
                <p class="text-xs text-gray-500 uppercase">Paid Status</p>
                <h3 class="text-sm font-semibold">{{ $member->isPaid ? 'Paid' : 'Unpaid' }}</h3>
            </div>
            <div class="p-4 rounded-md border border-gray-200 bg-white">
                <p class="text-xs text-gray-500 uppercase">Transaction ID</p>
                <h3 class="text-sm font-semibold">{{ $member->transaction_no ?: 'N/A' }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Transactions</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">Type</th>
                        <th class="px-3 py-2 border">Amount</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $tx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border">{{ $tx['type'] }}</td>
                            <td class="px-3 py-2 border text-teal-700">₹{{ number_format($tx['amount'], 2) }}</td>
                            <td class="px-3 py-2 border">{{ $tx['status'] }}</td>
                            <td class="px-3 py-2 border">{{ \Carbon\Carbon::parse($tx['created_at'])->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-400 py-3">No transactions.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Withdrawals</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">Amount</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Requested</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $w)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border text-red-700">
                                ₹{{ number_format($w['amount'], 2) }}
                                <span class="block text-xs text-gray-500 mt-0.5">
                                    Service: ₹{{ number_format(($w['details']['service_charge'] ?? round($w['amount'] * 0.05, 2)), 2) }} |
                                    TDS: ₹{{ number_format(($w['details']['tds'] ?? round($w['amount'] * 0.02, 2)), 2) }} |
                                    Net: ₹{{ number_format(($w['details']['net_amount'] ?? round($w['amount'] * 0.93, 2)), 2) }}
                                </span>
                            </td>
                            <td class="px-3 py-2 border">{{ $w['status'] }}</td>
                            <td class="px-3 py-2 border">{{ \Carbon\Carbon::parse($w['created_at'])->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-400 py-3">No withdraw requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
