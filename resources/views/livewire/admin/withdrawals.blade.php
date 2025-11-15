<div class="w-full">
    <div class="w-full">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-slate-900">Withdrawal Requests</h2>
                <div class="flex items-center gap-2">
                    <select wire:model.live="status" class="border border-slate-200 rounded-md px-3 py-2">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="all">All</option>
                    </select>
                    <input type="text" wire:model.live="search" placeholder="Search by name or token" class="border border-slate-200 rounded-md px-3 py-2 w-64" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-slate-200 rounded-lg">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-3 py-2 border">Member</th>
                            <th class="px-3 py-2 border">Token</th>
                            <th class="px-3 py-2 border">Amount</th>
                            <th class="px-3 py-2 border">Net</th>
                            <th class="px-3 py-2 border">Status</th>
                            <th class="px-3 py-2 border">Requested</th>
                            <th class="px-3 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($withdrawals as $w)
                            <tr class="hover:bg-slate-50">
                                <td class="px-3 py-2 border">{{ $w->membership->name }}</td>
                                <td class="px-3 py-2 border">{{ $w->membership->token }}</td>
                                <td class="px-3 py-2 border">₹{{ number_format($w->amount, 2) }}</td>
                                <td class="px-3 py-2 border">₹{{ number_format(($w->details['net_amount'] ?? round($w->amount * 0.93, 2)), 2) }}</td>
                                <td class="px-3 py-2 border">{{ ucfirst($w->status) }}</td>
                                <td class="px-3 py-2 border">{{ $w->created_at->format('d M Y, h:i A') }}</td>
                                <td class="px-3 py-2 border">
                                    @if($w->status === 'pending')
                                        <button wire:click="approve({{ $w->id }})" class="px-3 py-1 bg-teal-600 text-white rounded mr-2">Approve</button>
                                        <button wire:click="reject({{ $w->id }})" class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
                                    @else
                                        <span class="text-slate-500">No actions</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-400 py-3">No withdrawals found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $withdrawals->links() }}
            </div>
        </div>
    </div>
</div>
