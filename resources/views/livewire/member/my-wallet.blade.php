<div class="bg-white border border-gray-100 rounded-xl shadow-md p-6 mt-6">
    @if($kycComplete)
    <h2 class="text-lg font-bold text-teal-700 mb-4">Commission Summary</h2>

    <!-- ✅ Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-50 p-4 rounded-md border border-green-100">
            <p class="text-xs text-gray-500 uppercase">Total Commission Earned </p>
            <h3 class="text-2xl font-bold text-green-700">₹{{ number_format($walletBalance, 2) }}</h3>
        </div>
        <div class="bg-indigo-50 p-4 rounded-md border border-indigo-100">
            <p class="text-xs text-gray-500 uppercase">Wallet Status</p>
            <h3 class="text-sm font-semibold {{ $isWalletLocked ? 'text-green-700' : 'text-red-600' }}">
                {{ $isWalletLocked ? 'Unlocked (Active)' : 'Locked (Inactive)' }}
            </h3>
        </div>
        <div class="bg-yellow-50 p-4 rounded-md border border-yellow-100">
            <p class="text-xs text-gray-500 uppercase">Commission Entries</p>
            <h3 class="text-lg font-semibold text-yellow-700">{{ count($commissionHistory) }}</h3>
        </div>
    </div>

    <!-- 🧾 Commission History Table -->
    <div class="overflow-x-auto">

        <div class="flex items-center gap-4 mb-4">
            <button wire:click="$set('binaryComissionHistory', true)" class="px-4 py-2 rounded-lg text-sm font-semibold 
            {{ $binaryComissionHistory ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                Upline Commission History
            </button>

            <button wire:click="$set('binaryComissionHistory', false)" class="px-4 py-2 rounded-lg text-sm font-semibold 
            {{ !$binaryComissionHistory ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                Referral Commission History
            </button>
        </div>


        @if ($binaryComissionHistory)
            <h3 class="text-md font-semibold text-gray-700 mb-3">Upline Commission History</h3>

            <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">Level</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Left Member</th>
                        <th class="px-3 py-2 border">Right Member</th>
                        <th class="px-3 py-2 border">Percentage</th>
                        <th class="px-3 py-2 border">Commission</th>
                        <th class="px-3 py-2 border">Details</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($commissionHistory as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border">{{ $row['level'] }}</td>
                            <td class="px-3 py-2 border">
                                @if ($row['status'] === 'Paid')
                                    <span class="text-green-700 font-semibold">Paid</span>
                                @elseif ($row['status'] === 'Pending')
                                    <span class="text-yellow-600 font-semibold">Pending</span>
                                @else
                                    <span class="text-gray-500">{{ $row['status'] }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 border text-blue-700">{{ $row['left_member'] }}</td>
                            <td class="px-3 py-2 border text-purple-700">{{ $row['right_member'] }}</td>
                            <td class="px-3 py-2 border">{{ $row['percentage'] }}</td>
                            <td class="px-3 py-2 border text-green-700 font-semibold">
                                ₹{{ number_format($row['commission'], 2) }}
                            </td>
                            <td class="px-3 py-2 border">{{ $row['detail'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-3">No commission yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif



        @if (!$binaryComissionHistory)
            <h3 class="text-md font-semibold text-gray-700 mb-3">Referral Commission History</h3>

            <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">Level</th>
                        <th class="px-3 py-2 border">Upline Token</th>
                        <th class="px-3 py-2 border">Commission %</th>
                        <th class="px-3 py-2 border">Commission</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($referralComissionHistory as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border">{{ $row['level'] }}</td>
                            <td class="px-3 py-2 border">{{ $row['upline_id'] }}</td>
                            <td class="px-3 py-2 border">{{ $row['percentage'] }}%</td>
                            <td class="px-3 py-2 border text-green-700">
                                ₹{{ number_format($row['commission'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-400 py-3">No referral commission yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        <div class="mt-6">
            <h3 class="text-md font-semibold text-gray-700 mb-3">Transactions</h3>
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
                            <td class="px-3 py-2 border text-green-700">₹{{ number_format($tx['amount'], 2) }}</td>
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

        <div class="mt-6">
            <h3 class="text-md font-semibold text-gray-700 mb-3">Withdraw</h3>
            <div class="flex items-center gap-2">
                <input type="number" step="0.01" wire:model="withdrawAmount" class="border rounded-md px-3 py-2 w-48" placeholder="Amount">
                <button wire:click="withdraw" class="px-4 py-2 bg-teal-600 text-white rounded-md">Request Withdraw</button>
            </div>
            <table class="min-w-full text-sm border border-gray-200 rounded-lg mt-3">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">Amount</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $w)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border text-red-700">₹{{ number_format($w['amount'], 2) }}</td>
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
    @else
    <h2 class="text-lg font-bold text-red-700">Complete KYC to access Wallet</h2>
    <p class="text-sm text-gray-600 mt-2">Update profile details to enable transactions and withdrawals.</p>
    @endif
</div>
