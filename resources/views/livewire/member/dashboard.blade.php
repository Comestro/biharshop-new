<div>
    @php
        $kycIncomplete = !($membership->father_name && $membership->mother_name && $membership->home_address && $membership->city && $membership->state && $membership->pincode && $membership->bank_name && $membership->account_no && $membership->ifsc && $membership->pancard && $membership->aadhar_card && $membership->image && $membership->terms_and_condition);
    @endphp

    @if($kycIncomplete)
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-8-3a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Complete Your KYC</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Your profile is incomplete. Please provide your address, nominee, bank and document details to
                            activate your membership.</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('membership.register') }}"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Complete KYC
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(!$membership->isPaid)
        <div class="mb-6 bg-amber-50 border-l-4 border-amber-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
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

    <!-- Member Header Full Dashboard Box -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">

        <!-- Top Banner -->
        <div class="bg-gradient-to-r from-teal-600 to-teal-500 h-24"></div>

        <div class="px-6 pb-6 -mt-12">
            <div class="flex flex-col sm:flex-row items-center sm:items-start">

                <!-- Profile Image -->
                <div class="relative">
                    <div class="h-28 w-28 rounded-full bg-blue-100 flex items-center justify-center 
                border-4 border-white shadow-lg">
                        <span class="text-3xl font-bold text-blue-600">A</span>
                    </div>
                </div>

                <!-- Member Details -->
                <div class="mt-4 sm:mt-0 sm:ml-6 text-center sm:text-left w-full">

                    <h1
                        class="text-2xl font-bold text-gray-900 flex items-center justify-center sm:justify-start gap-2">
                        Ankur Jha
                        <span class="text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </h1>

                    <!-- Member ID -->
                    <p class="text-gray-600 text-sm flex items-center justify-center sm:justify-start gap-1 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Member ID: <span class="font-semibold">MBR12345</span>
                    </p>

                    <!-- Status -->
                    <div class="mt-3">
                        <span class="px-3 py-1 rounded-full text-xs font-medium shadow bg-green-100 text-green-700">
                            Active Member
                        </span>
                    </div>

                    <!-- EXTRA STATIC DETAILS -->
                    <div class="mt-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">

                        <!-- Total Upline -->
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-center shadow-sm">
                            <p class="text-xs text-gray-500">Total Upline</p>
                            <p class="text-xl font-bold text-gray-800">8</p>
                        </div>

                        <!-- Total Downline -->
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-center shadow-sm">
                            <p class="text-xs text-gray-500">Total Downline</p>
                            <p class="text-xl font-bold text-gray-800">32</p>
                        </div>

                        <!-- Direct Referrals -->
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-center shadow-sm">
                            <p class="text-xs text-gray-500">Direct</p>
                            <p class="text-xl font-bold text-gray-800">5</p>
                        </div>

                        <!-- Team Members -->
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-center shadow-sm">
                            <p class="text-xs text-gray-500">Team Members</p>
                            <p class="text-xl font-bold text-gray-800">40</p>
                        </div>

                        <!-- Wallet Balance -->
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-center shadow-sm">
                            <p class="text-xs text-gray-500">Wallet Balance</p>
                            <p class="text-xl font-bold text-blue-600">₹1,520</p>
                        </div>

                        <!-- Joining Date -->
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-center shadow-sm">
                            <p class="text-xs text-gray-500">Joining</p>
                            <p class="text-xl font-bold text-gray-800">12 Jan 2025</p>
                        </div>
                    </div>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
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