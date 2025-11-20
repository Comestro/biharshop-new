<div class="p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">E-PIN Manager</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        {{-- Generate PINs --}}
        <div class="border rounded-lg p-4">
            <p class="text-sm font-medium text-gray-800 mb-2">Generate Pins</p>

            <div class="space-y-3">
                <input type="number" wire:model="generateCount" class="w-full border rounded px-3 py-2"
                    placeholder="Count">

                <input type="number" wire:model="planAmount" class="w-full border rounded px-3 py-2"
                    placeholder="Plan Amount">

                <input type="text" wire:model="planName" class="w-full border rounded px-3 py-2"
                    placeholder="Plan Name">

                <input type="text" wire:model.live="membershipId" class="w-full border rounded px-3 py-2"
                    placeholder="Assign to Membership Id (optional)">

                <h2 class="text-sm text-gray-700">
                    {{ $transferMemberName ?? '' }}
                </h2>

                <button wire:click="generate"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Generate
                </button>
            </div>
        </div>

        {{-- Transfer --}}
        <div class="border rounded-lg p-4">
            <p class="text-sm font-medium text-gray-800 mb-2">Transfer Pin</p>

            <div class="space-y-3">
                <input type="text" wire:model="transferCode" class="w-full border rounded px-3 py-2"
                    placeholder="Pin Code">

                <input type="email" wire:model="transferToEmail" class="w-full border rounded px-3 py-2"
                    placeholder="Recipient Email">

                <button wire:click="transfer"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Transfer
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
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

                        <td class="px-3 py-2 text-sm">
                            {{ $pin->plan_name }} (₹{{ number_format($pin->plan_amount, 2) }})
                        </td>

                        <td class="px-3 py-2 text-sm">
                            {{ $pin->owner?->email ?: '-' }}
                        </td>

                        <td class="px-3 py-2 text-sm">{{ $pin->status }}</td>

                        <td class="px-3 py-2 text-sm">
                            {{ $pin->usedBy?->token ?: '-' }}
                        </td>

                        <td class="px-3 py-2 text-sm">
                            {{ $pin->used_at ? \Carbon\Carbon::parse($pin->used_at)->format('d M Y, h:i A') : '-' }}
                        </td>

                        <td class="px-3 py-2 text-sm">
                            <a href="{{ route('register') }}?epin={{ $pin->code }}&token={{ \App\Models\Membership::where('user_id', $pin->owner_user_id)->first()?->token }}"
                                class="inline-block px-3 py-1 rounded bg-teal-600 text-white hover:bg-teal-700 
                                                                    {{ $pin->status !== 'available' ? 'opacity-50 pointer-events-none' : '' }}">
                                Join
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>