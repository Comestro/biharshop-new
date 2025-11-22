<div class="py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- SHOW MY TOKEN + PARENT --}}
        @php
            $mem = auth()->user()->membership ?? null;
            $myToken = $mem?->membership_id ?? null;

            $parent = null;
            if ($mem) {
                $pos = \App\Models\BinaryTree::where('member_id', $mem->id)->first();
                if ($pos && $pos->parent_id) {
                    $parent = \App\Models\Membership::find($pos->parent_id);
                } elseif ($mem->referal_id) {
                    $parent = \App\Models\Membership::find($mem->referal_id);
                }
            }
        @endphp

        @if($myToken)
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4">
                    <p class="text-xs text-indigo-700">My Token</p>
                    <p class="text-lg font-bold text-indigo-800">{{ $myToken }}</p>
                </div>

                <div class="bg-gray-50 border rounded-lg p-4 md:col-span-2">
                    <p class="text-xs text-gray-700">Parent</p>
                    <p class="text-sm text-gray-800">
                        {{ $parent?->name ?? '-' }} | Member ID: {{ $parent?->membership_id ?? '-' }}
                    </p>
                </div>

            </div>
        @endif


        {{-- TITLE --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">My E-PINs</h2>
            <p class="text-sm text-gray-600">Transfer or redeem pins</p>
        </div>


        {{-- TRANSFER PIN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            <div class="border rounded-lg p-4">
                <p class="text-sm font-medium text-gray-800 mb-2">Transfer Pin</p>

                <div class="space-y-3">

                    <input type="text" wire:model.live="transferCode" class="w-full border rounded px-3 py-2"
                        placeholder="PIN Code">
                    @error($transferCode)
                        {{ $message }}
                    @enderror

                    <input type="text" wire:model.live="transferToMemberId" class="w-full border rounded px-3 py-2"
                        placeholder="Recipient Membership ID">

                    {{-- LIVE NAME SHOW --}}
                    <p class="text-sm text-gray-700">
                        {{ $transferMemberName ?? '' }}
                    </p>

                    <button wire:click="transfer"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        Transfer
                    </button>

                </div>
            </div>


            {{-- REDEEM (Optional)
            <div class="border rounded-lg p-4">
                <p class="text-sm font-medium text-gray-800 mb-2">Redeem Pin</p>

                <div class="space-y-3">
                    <input type="text" wire:model="redeemCode" class="w-full border rounded px-3 py-2"
                        placeholder="PIN Code">

                    <button wire:click="redeem"
                        class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition">
                        Redeem
                    </button>
                </div>
            </div>
            --}}
        </div>


        {{-- PIN LIST --}}
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-semibold">Code</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold">Plan</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold">Status</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold">Used At</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold">Join</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pins as $pin)
                        <tr>
                            <td class="px-3 py-2 text-sm">{{ $pin->code }}</td>

                            <td class="px-3 py-2 text-sm">
                                {{ $pin->plan_name }}
                                (₹{{ number_format($pin->plan_amount, 2) }})
                            </td>

                            <td class="px-3 py-2 text-sm">
                                {{ $pin->status == 'used' ? 'Used' : 'Available' }}

                                @if($pin->usedBy)
                                    — Used by:
                                    {{ $pin->usedBy->name }} ({{ $pin->usedBy->membership_id }})
                                @endif
                            </td>

                            <td class="px-3 py-2 text-sm">
                                {{ $pin->used_at ? \Carbon\Carbon::parse($pin->used_at)->format('d M Y, h:i A') : '-' }}
                            </td>

                            <td class="px-3 py-2 text-sm">
                                <a href="{{ route('register') }}?epin={{ $pin->code }}&sponsor_id={{ $myToken }}"
                                    class="inline-block px-3 py-1 rounded bg-teal-600 text-white hover:bg-teal-700 {{ $pin->status == 'used' ? 'opacity-50 pointer-events-none' : '' }}">
                                    Join
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>