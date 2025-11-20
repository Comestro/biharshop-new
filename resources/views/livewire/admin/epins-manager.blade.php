<div class="p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">E-PIN Manager</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="border rounded-lg p-4">
            <p class="text-sm font-medium text-gray-800 mb-2">Generate Pins</p>
            <div class="space-y-3">
                <select wire:model="selectedPlanId" class="w-full border rounded px-3 py-2">
                    <option value="">Select Plan</option>
                    @foreach($plans ?? [] as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ number_format($plan->price,2) }})</option>
                    @endforeach
                </select>
                <input type="number" wire:model="generateCount" class="w-full border rounded px-3 py-2" placeholder="Count">
                <button wire:click="generate" class="px-4 py-2 bg-indigo-600 text-white rounded">Generate</button>
            </div>
        </div>
        <div class="border rounded-lg p-4">
            <p class="text-sm font-medium text-gray-800 mb-2">Transfer Pin</p>
            <div class="space-y-3">
                <input type="number" wire:model="bulkTransferQty" class="w-full border rounded px-3 py-2" placeholder="Qty">
                <input type="text" wire:model="bulkTransferMemberId" class="w-full border rounded px-3 py-2" placeholder="Member ID">
                <button wire:click="bulkTransfer" class="px-4 py-2 bg-indigo-600 text-white rounded">Transfer Available</button>
            </div>
            
        </div>
    </div>

    <div class="border rounded-lg p-4 mb-4">
        <p class="text-sm font-medium text-gray-800 mb-2">Filter Pins</p>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <select wire:model="filterStatus" class="border rounded px-3 py-2">
                <option value="all">All Status</option>
                <option value="available">Available</option>
                <option value="transferred">Transferred</option>
                <option value="used">Used</option>
            </select>
            <select wire:model="filterPlanId" class="border rounded px-3 py-2">
                <option value="">All Plans</option>
                @foreach($plans ?? [] as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ number_format($plan->price,2) }})</option>
                @endforeach
            </select>
            <input type="text" wire:model="filterOwnerEmail" class="border rounded px-3 py-2" placeholder="Filter by owner email">
        </div>
    </div>

    <div class="border rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Code</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Plan</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Owner</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Status</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Used By</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Used At</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Join</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pins as $pin)
                <tr>
                    <td class="px-3 py-2 text-sm">{{ $pin->code }}</td>
                    <td class="px-3 py-2 text-sm">{{ $pin->plan_name }} (₹{{ number_format($pin->plan_amount,2) }})</td>
                    <td class="px-3 py-2 text-sm">{{ $pin->owner?->email ?: '-' }}</td>
                    <td class="px-3 py-2 text-sm">{{ $pin->status }}</td>
                    <td class="px-3 py-2 text-sm">{{ $pin->usedBy?->token ?: '-' }}</td>
                    <td class="px-3 py-2 text-sm">{{ $pin->used_at ? \Carbon\Carbon::parse($pin->used_at)->format('d M Y, h:i A') : '-' }}</td>
                    <td class="px-3 py-2 text-sm">
                        <a href="{{ route('register') }}?epin={{ $pin->code }}&token={{ \App\Models\Membership::where('user_id', $pin->owner_user_id)->first()?->token }}"
                           class="inline-block px-3 py-1 rounded bg-teal-600 text-white hover:bg-teal-700 {{ $pin->status !== 'available' ? 'opacity-50 pointer-events-none' : '' }}">
                            Join
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
