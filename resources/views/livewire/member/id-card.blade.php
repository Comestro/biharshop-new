<div>
    @if (($membership->kyc_status ?? 'pending') !== 'approved')
        <div
            class="mb-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg flex items-center justify-between">
            <div>Complete KYC to download your ID card.</div>
            <a href="{{ route('member.kyc-documents') }}" class="px-3 py-1.5 bg-yellow-600 text-white rounded">Go to
                KYC</a>
        </div>
    @endif

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Member ID Card</h2>
        <div class="flex gap-3">
            @if (($membership->kyc_status ?? 'pending') === 'approved')
                <button id="downloadPng"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Download Image</button>
                <button onclick="window.print()"
                    class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">Print</button>
            @else
                <button class="px-4 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed" disabled>Download
                    Image</button>
                <button class="px-4 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed"
                    disabled>Print</button>
            @endif
        </div>
    </div>

    <div id="idCard" class="max-w-2xl border rounded-2xl overflow-hidden bg-white shadow-lg">
        <div class=" px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="font-bold text-2xl tracking-wide">{{ 'BiharShop Ecommerce Pvt. Ltd.' }}</div>
            </div>
            <div class="text-right">
                <div class="text-sm">Direct Seller ID</div>
                <div class="text-lg font-semibold">{{ $membership->membership_id }}</div>
            </div>
        </div>

        <div class="px-6 py-5 grid grid-cols-3 gap-6">
            <div class="col-span-2 space-y-3">
                <div class="flex gap-2">
                    <span class="text-xs text-gray-500">Name</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $membership->name }}</span>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Address</div>
                    <div class="text-sm font-semibold text-gray-900">
                        {{ $membership->home_address }}
                        @if ($membership->city)
                            , {{ $membership->city }}
                        @endif
                        @if ($membership->state)
                            , {{ $membership->state }}
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <span class="text-xs text-gray-500">Contact No.</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $membership->mobile }}</span>
                </div>
                <div class="">
                    <div class="text-xs text-gray-500">Authorized Signatory</div>
                    <div class="mt-2 h-1 w-28 border-t border-gray-300"></div>

                    <!-- Signature Placeholder Image -->
                    <div class="">
                        <img src="{{ $membership->signature ? Storage::url($membership->signature) : null }}"
                            alt="Signature" class="h-10 w-28 object-cover rounded border" />
                    </div>
                </div>

            </div>
            <div class="flex items-start justify-end">
                @php $img = $membership->image ? Storage::url($membership->image) : null; @endphp
                @if ($img)
                    <div class="p-1.5 bg-gray-100 rounded-lg border">
                        <img src="{{ $img }}" class="w-28 h-32 object-cover rounded" />
                    </div>
                @else
                    <div
                        class="w-28 h-32 border rounded bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                        No Photo</div>
                @endif
            </div>
        </div>

        <div class="px-6 py-3 text-xs text-gray-600 border-t flex items-center justify-between">
            <div>Id Card No: BSEID000{{ $membership->id ?? 'N/A' }}</div>
        </div>

        @if (($membership->kyc_status ?? 'pending') !== 'approved')
            <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                <div class="text-3xl font-bold text-gray-300 rotate-[-18deg]">KYC Pending</div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        document.getElementById('downloadPng')?.addEventListener('click', async function() {
            const card = document.getElementById('idCard');
            const canvas = await html2canvas(card, {
                scale: 2,
                backgroundColor: null
            });
            const dataUrl = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = 'member-id-{{ $membership->membership_id }}.png';
            link.click();
        });
    </script>

    <style>
        @media print {
            body {
                background: white;
            }

            #idCard {
                box-shadow: none;
                border: 1px solid #e5e7eb;
            }

            .flex.items-center.justify-between.mb-4 {
                display: none !important;
            }
        }
    </style>
</div>
