<div class="w-full">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 px-4 sm:px-0">
            Welcome to Admin Dashboard
        </h2>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 px-4 sm:px-0">
            <!-- Total Members -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-50">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-bold text-gray-900">
                                        {{ $stats['total_members'] }}
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Total Members
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Verifications -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-50">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-bold text-gray-900">
                                        {{ $stats['pending_verifications'] }}
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Pending Verifications
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
          
            <!-- Verified Members -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-teal-50">
                            <svg class="h-8 w-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-bold text-gray-900">
                                        {{ $stats['verified_members'] }}
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Verified Members
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paid / Unpaid -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-50">
                            <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline gap-6">
                                    <div>
                                        <div class="text-2xl font-bold text-green-700">{{ $stats['paid_members'] }}</div>
                                        <div class="text-xs text-gray-500">Paid</div>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-bold text-red-600">{{ $stats['unpaid_members'] }}</div>
                                        <div class="text-xs text-gray-500">Unpaid</div>
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Payment Status
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today New Members -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-50">
                            <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-bold text-gray-900">
                                        {{ $stats['today_new_members'] }}
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    New Members Today
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wallet & Withdrawals -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-sky-50">
                            <svg class="h-8 w-8 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline gap-6">
                                    <div>
                                        <div class="text-xl font-bold text-teal-700">₹{{ number_format($stats['wallet_credits_sum'], 2) }}</div>
                                        <div class="text-xs text-gray-500">Total Credits</div>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold text-teal-700">₹{{ number_format($stats['wallet_credits_today'], 2) }}</div>
                                        <div class="text-xs text-gray-500">Today ({{ $stats['wallet_credits_today_count'] }})</div>
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Wallet Activity
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-50">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline gap-6">
                                    <div>
                                        <div class="text-xl font-bold text-red-700">₹{{ number_format($stats['withdraw_pending_sum'], 2) }}</div>
                                        <div class="text-xs text-gray-500">Pending ({{ $stats['withdraw_pending_count'] }})</div>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold text-green-700">₹{{ number_format($stats['withdraw_approved_sum'], 2) }}</div>
                                        <div class="text-xs text-gray-500">Approved</div>
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Withdrawals
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Binary Links -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-gray-100">
                            <svg class="h-8 w-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-bold text-gray-900">
                                        {{ $stats['binary_links'] }}
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Binary Links Created
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6 px-4 sm:px-0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 lg:col-span-1">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Top Referrers</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-3 py-2 border">Name</th>
                                <th class="px-3 py-2 border">Token</th>
                                <th class="px-3 py-2 border">Referrals</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topReferrers as $r)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 border">{{ $r->name }}</td>
                                    <td class="px-3 py-2 border">{{ $r->token }}</td>
                                    <td class="px-3 py-2 border">{{ $r->referrals_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-400 py-3">No data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 lg:col-span-2">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Recent Members</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-3 py-2 border">Name</th>
                                <th class="px-3 py-2 border">Token</th>
                                <th class="px-3 py-2 border">Paid</th>
                                <th class="px-3 py-2 border">Verified</th>
                                <th class="px-3 py-2 border">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMembers as $m)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 border">{{ $m->name }}</td>
                                    <td class="px-3 py-2 border">{{ $m->token }}</td>
                                    <td class="px-3 py-2 border">{{ $m->isPaid ? 'Yes' : 'No' }}</td>
                                    <td class="px-3 py-2 border">{{ $m->isVerified ? 'Yes' : 'No' }}</td>
                                    <td class="px-3 py-2 border">{{ $m->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-400 py-3">No recent members</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 sm:px-0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Recent Withdrawals</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-3 py-2 border">Member</th>
                                <th class="px-3 py-2 border">Token</th>
                                <th class="px-3 py-2 border">Amount</th>
                                <th class="px-3 py-2 border">Status</th>
                                <th class="px-3 py-2 border">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentWithdrawals as $w)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 border">{{ $w->membership->name }}</td>
                                    <td class="px-3 py-2 border">{{ $w->membership->token }}</td>
                                    <td class="px-3 py-2 border">₹{{ number_format($w->amount, 2) }}</td>
                                    <td class="px-3 py-2 border">{{ ucfirst($w->status) }}</td>
                                    <td class="px-3 py-2 border">{{ $w->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-400 py-3">No recent withdrawals</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Recent Wallet Transactions</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-3 py-2 border">Member</th>
                                <th class="px-3 py-2 border">Token</th>
                                <th class="px-3 py-2 border">Type</th>
                                <th class="px-3 py-2 border">Amount</th>
                                <th class="px-3 py-2 border">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 border">{{ $t->membership->name ?? '-' }}</td>
                                    <td class="px-3 py-2 border">{{ $t->membership->token ?? '-' }}</td>
                                    <td class="px-3 py-2 border">{{ $t->type }}</td>
                                    <td class="px-3 py-2 border">₹{{ number_format($t->amount, 2) }}</td>
                                    <td class="px-3 py-2 border">{{ $t->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-400 py-3">No recent transactions</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
