<div>
    @if(!$membership->isPaid)
        <div class="mb-6 bg-amber-50 border-l-4 border-amber-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-800">Payment Required</h3>
                    <div class="mt-2 text-sm text-amber-700">
                        <p>Your membership is currently inactive. Please complete your payment to activate all features.</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('membership.payment', $membership) }}" 
                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-amber-700 bg-amber-100 hover:bg-amber-200">
                            Pay Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Member Header with Photo -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start">
            <div class="mb-4 sm:mb-0 sm:mr-6">
                @if ($membership->image)
                    <img src="{{ Storage::url($membership->image) }}" alt="{{ $membership->name }}" 
                         class="h-24 w-24 rounded-full object-cover border-2 border-gray-200">
                @else
                    <div class="h-24 w-24 rounded-full bg-blue-100 flex items-center justify-center border-2 border-gray-200">
                        <span class="text-2xl font-medium text-blue-500">{{ substr($membership->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="text-center sm:text-left">
                <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ $membership->name }}</h1>
                <p class="text-gray-500">Member ID: {{ $membership->token }}</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $membership->isVerified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $membership->isVerified ? 'Active' : 'Pending' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Direct Referrals</p>
                    <p class="text-2xl font-semibold">{{ $directReferrals }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Team Size</p>
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Left</p>
                            <p class="text-xl font-semibold">{{ $leftTeamSize }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Right</p>
                            <p class="text-xl font-semibold">{{ $rightTeamSize }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Total</p>
                            <p class="text-xl font-semibold">{{ $treeMembers }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-teal-100 mr-4">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Status</p>
                    <p class="text-2xl font-semibold">{{ $membership->isVerified ? 'Active' : 'Pending' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Details -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-medium">Member Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Personal Details</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membership->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membership->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mobile</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membership->mobile }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Bank Details</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membership->bank_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membership->account_no }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">IFSC Code</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membership->ifsc }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
