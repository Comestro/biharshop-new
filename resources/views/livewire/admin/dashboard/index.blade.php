<div class="w-full">
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-gray-900">Welcome back, Admin</h2>
        <p class="text-gray-600 mt-2">Here's what's happening in your MLM network today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Total Members -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-blue-300 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Members</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_members'] }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Pending Verifications -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-yellow-300 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Verifications</p>
                    <p class="text-3xl font-bold text-yellow-700 mt-2">{{ $stats['pending_verifications'] }}</p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-xl">
                    <i class="fas fa-hourglass-half text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <!-- Verified Members -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-teal-300 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Verified Members</p>
                    <p class="text-3xl font-bold text-teal-700 mt-2">{{ $stats['verified_members'] }}</p>
                </div>
                <div class="p-4 bg-teal-50 rounded-xl">
                    <i class="fas fa-check-circle text-2xl text-teal-600"></i>
                </div>
            </div>
        </div>

        <!-- New Members Today -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-orange-300 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">New Today</p>
                    <p class="text-3xl font-bold text-orange-700 mt-2">{{ $stats['today_new_members'] }}</p>
                </div>
                <div class="p-4 bg-orange-50 rounded-xl">
                    <i class="fas fa-user-plus text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- Payment Status (Paid / Unpaid) -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-indigo-300 transition-colors lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Payment Status</p>
                    <div class="flex items-center gap-8 mt-3">
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['paid_members'] }}</p>
                            <p class="text-xs text-gray-500">Paid</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-red-600">{{ $stats['unpaid_members'] }}</p>
                            <p class="text-xs text-gray-500">Unpaid</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-indigo-50 rounded-xl">
                    <i class="fas fa-credit-card text-2xl text-indigo-600"></i>
                </div>
            </div>
        </div>

        <!-- Wallet Activity -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-sky-300 transition-colors lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Wallet Credits</p>
                    <div class="mt-3">
                        <p class="text-xl font-bold text-teal-700">₹{{ number_format($stats['wallet_credits_sum'], 0) }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Today: ₹{{ number_format($stats['wallet_credits_today'], 0) }} 
                            ({{ $stats['wallet_credits_today_count'] }} txns)
                        </p>
                    </div>
                </div>
                <div class="p-4 bg-sky-50 rounded-xl">
                    <i class="fas fa-wallet text-2xl text-sky-600"></i>
                </div>
            </div>
        </div>

        <!-- Withdrawals Overview -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-red-300 transition-colors lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Withdrawal Requests</p>
                    <div class="flex items-center gap-8 mt-3">
                        <div>
                            <p class="text-xl font-bold text-red-600">₹{{ number_format($stats['withdraw_pending_sum'], 0) }}</p>
                            <p class="text-xs text-gray-500">Pending ({{ $stats['withdraw_pending_count'] }})</p>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-green-600">₹{{ number_format($stats['withdraw_approved_sum'], 0) }}</p>
                            <p class="text-xs text-gray-500">Approved</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-red-50 rounded-xl">
                    <i class="fas fa-money-bill-wave text-2xl text-red-600"></i>
                </div>
            </div>
        </div>

        <!-- Binary Links Created -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-400 transition-colors lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Binary Links Created</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['binary_links'] }}</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-xl">
                    <i class="fas fa-project-diagram text-2xl text-gray-700"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Section: Tables -->
    <div class="mt-10 grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Top Referrers -->
        <div class="bg-white border border-gray-200 rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-trophy text-yellow-600"></i>
                    Top Referrers
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Token</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Refs</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topReferrers as $r)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $r->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $r->token }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-blue-600">{{ $r->referrals_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400 text-sm">No referrals yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Members + Recent Withdrawals (side by side on large screens) -->
        <div class="xl:col-span-2 grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Recent Members -->
            <div class="bg-white border border-gray-200 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-clock text-blue-600"></i>
                        Recent Members
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Token</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentMembers as $m)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $m->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $m->token }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($m->isPaid && $m->isVerified)
                                            <span class="text-green-600 font-medium">Active</span>
                                        @elseif($m->isPaid)
                                            <span class="text-yellow-600">Pending Verify</span>
                                        @else
                                            <span class="text-red-600">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500">{{ $m->created_at->format('d M, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">No recent members</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Withdrawals -->
            <div class="bg-white border border-gray-200 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-money-bill-wave text-red-600"></i>
                        Recent Withdrawals
                    </h3>
                    @if($stats['withdraw_pending_count'] > 0)
                        <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                            {{ $stats['withdraw_pending_count'] }} pending
                        </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Member</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentWithdrawals as $w)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $w->membership->name }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold">₹{{ number_format($w->amount, 0) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($w->status === 'pending')
                                            <span class="text-yellow-600 font-medium">Pending</span>
                                        @elseif($w->status === 'approved')
                                            <span class="text-green-600 font-medium">Approved</span>
                                        @else
                                            <span class="text-red-600">{{ ucfirst($w->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-400 text-sm">No recent withdrawals</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>