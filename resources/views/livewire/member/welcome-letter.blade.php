<div class="py-8">
    <div class="max-w-4xl mx-auto bg-white border rounded-xl shadow p-8">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome Letter</h1>
                <p class="text-sm text-gray-600">Bihar Shop ecommerce Pvt Ltd</p>
            </div>
            <div class="text-right">
                @php $year = date('Y');
                $letterNo = ($member?->id ?? 0) . '/' . $year; @endphp
                <p class="text-sm text-gray-600">Letter No: <span class="font-semibold">{{ $letterNo }}</span></p>
            </div>
        </div>

        <div class="mt-6 space-y-4 text-sm text-gray-800 leading-relaxed">
            <p>Dear Clients / Participants & Families,</p>
            <p>We are Bihar Shop ecommerce Pvt Ltd, pleased to welcome you as our new client and take this opportunity
                to extend our warm greetings to you.</p>
            <p>We assure you that you will find it enjoyable and professionally beneficial to avail our services or to
                associate with us.</p>
            <p>We, at Bihar Shop ecommerce Pvt Ltd, respect the concern of our clients and associates and observe the
                highest degree of corporate ethics. We sincerely hope and believe that our services will exceed your
                expectations and add your name to our long list of satisfied clients and associates.</p>
            <p>We are confident that our customized packages will win your trust and appreciation. Our representatives
                are always available 24×7 with dedication to address all your concerns. We look forward to serving you
                for a long time.</p>
        </div>

        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-900">Enrollment Details</h2>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="border rounded-lg p-3">
                    <div class="flex justify-between"><span class="text-gray-600">ID No</span><span
                            class="font-medium">{{ $member?->token ?? '-' }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">Name</span><span
                            class="font-medium">{{ $member?->name ?? '-' }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">Address</span><span
                            class="font-medium">{{ $member?->home_address ?? '-' }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">City</span><span
                            class="font-medium">{{ $member?->city ?? '-' }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">District</span><span
                            class="font-medium">{{ $member?->city ?? '-' }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">State</span><span
                            class="font-medium">{{ $member?->state ?? '-' }}</span></div>
                </div>
                <div class="border rounded-lg p-3">
                    <div class="flex justify-between"><span class="text-gray-600">Mobile No</span><span
                            class="font-medium">{{ $member?->mobile ?? '-' }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">Joining Date</span><span
                            class="font-medium">{{ $member?->created_at ? \Carbon\Carbon::parse($member->created_at)->format('d-M-Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">Sponsor ID</span><span
                            class="font-medium">{{ $parent?->token ?? '-' }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">Sponsor Name</span><span
                            class="font-medium">{{ $parent?->name ?? '-' }}</span></div>
                    @php $planName = $member?->plan->name ?? '—';
                    $planAmount = $member?->plan->price ?? null; @endphp
                    <div class="flex justify-between mt-1"><span class="text-gray-600">Joining Kit</span><span
                            class="font-medium">{{ $planName }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">Kit Amount</span><span
                            class="font-medium">{{ $planAmount ? number_format($planAmount, 2) : '—' }}</span></div>
                </div>
                <div class="border rounded-lg p-3 md:col-span-2">
                    <div class="flex justify-between"><span class="text-gray-600">Email ID</span><span
                            class="font-medium">{{ $member?->email ?? '-' }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">Pan No</span><span
                            class="font-medium">{{ $member?->pancard ?? '-' }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">Password</span><span
                            class="font-medium">Not displayed</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">Membership Id</span><span
                            class="font-medium">{{ $member?->membership_id ?? '-' }}</span></div>
                    <div class="flex justify-between mt-1"><span class="text-gray-600">epin Password</span><span
                            class="font-medium">{{ decrypt(Auth::user()->membership->epin_password) }}</span></div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <p class="text-sm text-gray-800">See you at the Top,</p>
            <p class="text-sm font-semibold text-gray-900">CMD</p>
        </div>

        <div class="mt-8 flex items-center gap-3">
            @if($token)
                <a href="{{ route('register') }}?epin={{ $token }}"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md">Join (Direct)</a>
                <a href="{{ route('register') }}?epin={{ $token }}&position=left"
                    class="px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-md">Join Left</a>
                <a href="{{ route('register') }}?epin={{ $token }}&position=right"
                    class="px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-md">Join Right</a>
            @endif
            <button onclick="window.print()" class="ml-auto px-4 py-2 bg-gray-800 text-white rounded-md">Print
                Letter</button>
        </div>
    </div>
</div>