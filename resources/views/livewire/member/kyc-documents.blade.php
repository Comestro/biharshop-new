<div>
    @if(session()->has('message'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">KYC Documents</h2>

        @if($membership->kyc_status === 'approved')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PAN Card Image</label>
                    @if($membership->pancard_image)
                        <img src="{{ Storage::url($membership->pancard_image) }}" class="mt-3 rounded-lg border w-48" />
                    @else
                        <span class="text-sm text-gray-500">Not uploaded</span>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aadhar Card Image</label>
                    @if($membership->aadhar_card_image)
                        <img src="{{ Storage::url($membership->aadhar_card_image) }}" class="mt-3 rounded-lg border w-48" />
                    @else
                        <span class="text-sm text-gray-500">Not uploaded</span>
                    @endif
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload PAN Card Image</label>
                    <input type="file" wire:model="pancard_image" accept="image/*" class="w-full border rounded-lg p-2">
                    @error('pancard_image')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                    @if($pancard_image && method_exists($pancard_image, 'temporaryUrl'))
                        <img src="{{ $pancard_image->temporaryUrl() }}" class="mt-3 rounded-lg border w-48" />
                    @elseif($membership->pancard_image)
                        <img src="{{ Storage::url($membership->pancard_image) }}" class="mt-3 rounded-lg border w-48" />
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Aadhar Card Image</label>
                    <input type="file" wire:model="aadhar_card_image" accept="image/*" class="w-full border rounded-lg p-2">
                    @error('aadhar_card_image')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                    @if($aadhar_card_image && method_exists($aadhar_card_image, 'temporaryUrl'))
                        <img src="{{ $aadhar_card_image->temporaryUrl() }}" class="mt-3 rounded-lg border w-48" />
                    @elseif($membership->aadhar_card_image)
                        <img src="{{ Storage::url($membership->aadhar_card_image) }}" class="mt-3 rounded-lg border w-48" />
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <button wire:click="save" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save Documents</button>
            </div>
        @endif

        <div class="mt-6">
            <p class="text-sm">Status: <span class="font-semibold">
                {{ $membership->kyc_status ?? 'pending' }}
            </span></p>
            @if($membership->kyc_rejection_reason)
                <p class="text-sm text-red-600">Reason: {{ $membership->kyc_rejection_reason }}</p>
            @endif
        </div>
    </div>
</div>

