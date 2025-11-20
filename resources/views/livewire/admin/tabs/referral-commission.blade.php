<div class="space-y-6">
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Referral Commission Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-md border border-gray-200 bg-white">
                <p class="text-xs text-gray-500 uppercase">Total</p>
                <h3 class="text-2xl font-bold text-teal-700">₹{{ number_format($referralCommissionTotal, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Referral Commission Transactions</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">Level</th>
                        <th class="px-3 py-2 border">Child</th>
                        <th class="px-3 py-2 border">Percentage</th>
                        <th class="px-3 py-2 border">Amount</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($referralCommissionTx as $tx)
                        @php($m = $tx['meta'] ?? [])
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border">{{ $m['level'] ?? '-' }}</td>
                            <td class="px-3 py-2 border">{{ $m['child_id'] ?? '-' }}</td>
                            <td class="px-3 py-2 border">{{ isset($m['percentage']) ? $m['percentage'].'%' : '-' }}</td>
                            <td class="px-3 py-2 border text-teal-700">₹{{ number_format($tx['amount'], 2) }}</td>
                            <td class="px-3 py-2 border">{{ $tx['status'] }}</td>
                            <td class="px-3 py-2 border">{{ \Carbon\Carbon::parse($tx['created_at'])->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 py-3">No referral commissions.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
