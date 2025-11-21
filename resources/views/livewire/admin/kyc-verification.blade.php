<div>
    @if(session()->has('message'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">KYC Verification</h2>
            <div class="flex items-center gap-3">
                <input type="text" wire:model.live="search" placeholder="Search name or ID" class="border rounded-lg px-3 py-2">
                <select wire:model.live="filter" class="border rounded-lg px-3 py-2">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($members as $m)
                <div class="border rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-semibold">{{ $m->name }}</div>
                        <div class="text-xs text-gray-500">ID: {{ $m->membership_id }}</div>
                    </div>
                    <div class="text-sm text-gray-600 mb-2">Status: {{ $m->kyc_status ?? 'pending' }}</div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            @if($m->pancard_image)
                                <img src="{{ Storage::url($m->pancard_image) }}" class="rounded border mb-2" />
                            @else
                                <div class="text-xs text-gray-400">No PAN image</div>
                            @endif
                            <div class="text-xs text-gray-700 mt-1">PAN Number: <span class="font-semibold">{{ $m->pancard ?? '-' }}</span></div>
                        </div>
                        <div>
                            @if($m->aadhar_card_image)
                                <img src="{{ Storage::url($m->aadhar_card_image) }}" class="rounded border mb-2" />
                            @else
                                <div class="text-xs text-gray-400">No Aadhar image</div>
                            @endif
                            <div class="text-xs text-gray-700 mt-1">Aadhar Number: <span class="font-semibold">{{ $m->aadhar_card ?? '-' }}</span></div>
                        </div>
                    </div>
                    @if($m->kyc_rejection_reason)
                        <div class="mt-2 text-xs text-red-600">Reason: {{ $m->kyc_rejection_reason }}</div>
                    @endif
                    <div class="mt-3 flex items-center gap-3">
                        <button wire:click="approve({{ $m->id }})" class="px-3 py-1.5 bg-green-600 text-white rounded">Approve</button>
                        <button wire:click="reject({{ $m->id }}, 'Document unclear')" class="px-3 py-1.5 bg-red-600 text-white rounded">Reject</button>
                    </div>
                </div>
            @endforeach
        </div>

        @if(method_exists($members, 'links'))
            <div class="mt-4">{{ $members->links() }}</div>
        @endif
    </div>
</div>

