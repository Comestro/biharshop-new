<div class="bg-white border border-gray-100 rounded-xl shadow-md p-6 mt-6">
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
        <h3 class="text-md font-semibold text-gray-700 mb-3">Detailed Commission History</h3>
        <table class="min-w-full text-sm border border-gray-200 rounded-lg">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-3 py-2 text-left border">Level</th>
                    <th class="px-3 py-2 text-left border">Status</th>
                    <th class="px-3 py-2 text-left border">Left Member(s)</th>
                    <th class="px-3 py-2 text-left border">Right Member(s)</th>
                    <th class="px-3 py-2 text-left border">Percentage</th>
                    <th class="px-3 py-2 text-left border">Commission</th>
                    <th class="px-3 py-2 text-left border">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($commissionHistory as $row)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 py-2 border font-medium">{{ $row['level'] }}</td>
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
                        <td class="px-3 py-2 border text-gray-700">{{ $row['percentage'] }}</td>
                        <td class="px-3 py-2 border text-green-700 font-semibold">
                            ₹{{ number_format($row['commission'], 2) }}
                        </td>
                        <td class="px-3 py-2 border text-gray-600 leading-snug">
                            {{ $row['detail'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-3">No commission yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>