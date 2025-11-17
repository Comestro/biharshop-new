<div>
    @php
        $kycIncomplete = !($membership->father_name && $membership->mother_name && $membership->home_address && $membership->city && $membership->state && $membership->bank_name && $membership->account_no && $membership->ifsc && $membership->pancard && $membership->aadhar_card && $membership->image && $membership->terms_and_condition);
    @endphp


    @if($kycIncomplete)
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-8-3a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-base font-semibold text-blue-900">Complete Your KYC</h3>
                    <p class="mt-2 text-sm text-blue-700">Your profile is incomplete. Please provide your address, nominee, bank and document details to activate your membership.</p>
                    <div class="mt-4">
                        <a href="{{ route('membership.register') }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            Complete KYC
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    

    <!-- Member Header with Photo -->
    <div class="bg-white border border-gray-200 rounded-lg p-8 mb-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
            <div class="flex items-center mb-6 lg:mb-0">
                <div class="mr-6">
                    @if ($membership->image)
                        <img src="{{ Storage::url($membership->image) }}" alt="{{ $membership->name }}" 
                             class="h-24 w-24 rounded-full object-cover border-4 border-gray-100">
                    @else
                        <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center border-4 border-gray-100">
                            <span class="text-3xl font-semibold text-indigo-600">{{ substr($membership->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-1">{{ $membership->name }}</h1>
                    <p class="text-gray-600 mb-2">Member ID: <span class="font-semibold text-gray-900">{{ $membership->isVerified ? $membership->token : 'N/A' }}</span></p>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                            {{ $membership->isVerified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $membership->isVerified ? 'Active Member' : 'Pending Verification' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg px-6 py-4">
                <p class="text-sm text-gray-600 mb-1">Wallet Balance</p>
                <p class="text-2xl font-bold text-indigo-600 mb-2">₹{{ number_format($walletBalance, 2) }}</p>
                <a href="{{ route('member.wallet') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">
                    View Wallet
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-6 mb-6">
        @php
            $planPerDay = 16;
            $planDays = 30;
            $planTotal = $planPerDay * $planDays;
            $mid = $membership->id ?? null;
            $dailyAchieved = 0;
            if ($mid) {
                $dailyAchieved = \App\Models\WalletTransaction::where('membership_id', $mid)
                    ->where('type', 'daily_commission')
                    ->where('status', 'confirmed')
                    ->sum('amount');
            }
            $dailyRemaining = max(0, $planTotal - $dailyAchieved);
            $daysAchieved = min($planDays, (int) floor($dailyAchieved / $planPerDay));
            $progressPct = min(100, $planTotal > 0 ? round(($dailyAchieved / $planTotal) * 100) : 0);
        @endphp
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold">Daily Cashback Ads</div>
                <h3 class="mt-2 text-lg font-semibold text-emerald-900">₹{{ number_format($planPerDay, 2) }} per day for {{ $planDays }} days (₹{{ number_format($planTotal, 2) }} total)</h3>
                <div class="mt-3 h-3 bg-emerald-100 rounded-full overflow-hidden">
                    <div class="h-3 bg-emerald-500" style="width: {{ $progressPct }}%"></div>
                </div>
                <p class="mt-1 text-xs text-emerald-800">Progress: {{ $progressPct }}%</p>
            </div>
            <div class="grid grid-cols-3 gap-4 w-full lg:w-auto">
                <div class="bg-white rounded-lg border border-emerald-200 p-4 text-center">
                    <p class="text-xs font-medium text-emerald-700">Achieved</p>
                    <p class="mt-1 text-xl font-bold text-emerald-900">₹{{ number_format($dailyAchieved, 2) }}</p>
                </div>
                <div class="bg-white rounded-lg border border-emerald-200 p-4 text-center">
                    <p class="text-xs font-medium text-emerald-700">Remaining</p>
                    <p class="mt-1 text-xl font-bold text-emerald-900">₹{{ number_format($dailyRemaining, 2) }}</p>
                </div>
                <div class="bg-white rounded-lg border border-emerald-200 p-4 text-center">
                    <p class="text-xs font-medium text-emerald-700">Days</p>
                    <p class="mt-1 text-xl font-bold text-emerald-900">{{ $daysAchieved }} / {{ $planDays }}</p>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
        <!-- Direct Referrals Card -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Direct Referrals</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $directReferrals }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Team Size Card -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Team Size</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $treeMembers }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="text-center flex-1">
                    <p class="text-xs text-gray-500 mb-1">Left Team</p>
                    <p class="text-lg font-bold text-gray-900">{{ $leftTeamSize }}</p>
                </div>
                <div class="w-px h-8 bg-gray-200"></div>
                <div class="text-center flex-1">
                    <p class="text-xs text-gray-500 mb-1">Right Team</p>
                    <p class="text-lg font-bold text-gray-900">{{ $rightTeamSize }}</p>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Membership Status</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $membership->isVerified ? 'Active' : 'Pending' }}</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Information Section -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Member Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Personal Details -->
                <div>
                    <h4 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Personal Details</h4>
                    <dl class="space-y-4">
                        <div class="flex justify-between py-2">
                            <dt class="text-sm font-medium text-gray-600">Full Name</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $membership->name }}</dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-sm font-medium text-gray-600">Email Address</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $membership->email }}</dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-sm font-medium text-gray-600">Mobile Number</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $membership->mobile }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Bank Details -->
                <div>
                    <h4 class="text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Bank Details</h4>
                    <dl class="space-y-4">
                        <div class="flex justify-between py-2">
                            <dt class="text-sm font-medium text-gray-600">Bank Name</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $membership->bank_name ?: 'Not provided' }}</dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-sm font-medium text-gray-600">Account Number</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $membership->account_no ?: 'Not provided' }}</dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-sm font-medium text-gray-600">IFSC Code</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $membership->ifsc ?: 'Not provided' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
