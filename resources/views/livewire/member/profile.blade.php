    <div class="w-full">
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="p-6 sm:p-8 space-y-8">
                @php
                    $kycIncomplete = !($membership->father_name && $membership->mother_name && $membership->home_address && $membership->city && $membership->state && $membership->bank_name && $membership->account_no && $membership->ifsc && $membership->pancard && $membership->aadhar_card && $membership->image && $membership->terms_and_condition);
                @endphp

                @if($kycIncomplete)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-8-3a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-base font-semibold text-blue-900">KYC Incomplete</h3>
                                <p class="mt-1 text-sm text-blue-700">Your KYC is incomplete. Please finish your profile to enable verification and payouts.</p>
                                <a href="{{ route('membership.register') }}" class="mt-3 inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                    Complete KYC
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Profile Header -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 pb-8 border-b border-gray-200">
                    <div class="relative">
                        @if($membership->image)
                            <img src="{{ Storage::url($membership->image) }}" 
                                 alt="Profile photo" 
                                 class="h-28 w-28 rounded-full object-cover border-4 border-gray-100">
                        @else
                            <div class="h-28 w-28 rounded-full bg-indigo-100 flex items-center justify-center border-4 border-gray-100">
                                <span class="text-4xl font-semibold text-indigo-600">{{ substr($membership->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="absolute bottom-0 right-0 w-8 h-8 bg-white rounded-full border-2 border-gray-200 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-3xl font-bold text-gray-900">{{ $membership->name }}</h3>
                        <p class="text-gray-600 mt-1">Member ID: <span class="font-semibold text-gray-900">{{ $membership->isVerified ? $membership->token : 'N/A' }}</span></p>
                        <div class="mt-3 flex flex-wrap gap-2 justify-center sm:justify-start">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $membership->isVerified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $membership->isVerified ? '✓ Verified' : '⏳ Pending Verification' }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $membership->isPaid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $membership->isPaid ? '✓ Paid' : '✗ Payment Pending' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-semibold text-gray-900">Personal Information</h4>
                        <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Name</p><p class="text-base font-medium text-gray-900">{{ $membership->name ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Email</p><p class="text-base font-medium text-gray-900">{{ $membership->email ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Mobile</p><p class="text-base font-medium text-gray-900">{{ $membership->mobile ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">WhatsApp</p><p class="text-base font-medium text-gray-900">{{ $membership->whatsapp ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Date of Birth</p><p class="text-base font-medium text-gray-900">{{ $membership->date_of_birth ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Gender</p><p class="text-base font-medium text-gray-900">{{ $membership->gender ? ucfirst($membership->gender) : 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Nationality</p><p class="text-base font-medium text-gray-900">{{ $membership->nationality ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Marital Status</p><p class="text-base font-medium text-gray-900">{{ $membership->marital_status ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Religion</p><p class="text-base font-medium text-gray-900">{{ $membership->religion ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Father Name</p><p class="text-base font-medium text-gray-900">{{ $membership->father_name ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Mother Name</p><p class="text-base font-medium text-gray-900">{{ $membership->mother_name ?: 'Not provided' }}</p></div>
                    </div>
                </div>

                <!-- Bank Details -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-semibold text-gray-900">Bank Details</h4>
                        <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Bank Name</p>
                            <p class="text-base font-medium text-gray-900">{{ $membership->bank_name ?: 'Not provided' }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Branch Name</p>
                            <p class="text-base font-medium text-gray-900">{{ $membership->branch_name ?: 'Not provided' }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Account Number</p>
                            <p class="text-base font-medium text-gray-900">{{ $membership->account_no ?: 'Not provided' }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">IFSC Code</p>
                            <p class="text-base font-medium text-gray-900">{{ $membership->ifsc ?: 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Details -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-semibold text-gray-900">Address Details</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Home Address</p><p class="text-base font-medium text-gray-900">{{ $membership->home_address ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">City</p><p class="text-base font-medium text-gray-900">{{ $membership->city ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">State</p><p class="text-base font-medium text-gray-900">{{ $membership->state ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">PIN Code</p><p class="text-base font-medium text-gray-900">{{ $membership->pincode ?: 'Not provided' }}</p></div>
                    </div>
                </div>

                <!-- Nominee Details -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-semibold text-gray-900">Nominee Details</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Nominee Name</p><p class="text-base font-medium text-gray-900">{{ $membership->nominee_name ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Nominee Relation</p><p class="text-base font-medium text-gray-900">{{ $membership->nominee_relation ?: 'Not provided' }}</p></div>
                    </div>
                </div>

                <!-- Documents -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-semibold text-gray-900">Documents</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">PAN Card</p><p class="text-base font-medium text-gray-900">{{ $membership->pancard ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Aadhar Card</p><p class="text-base font-medium text-gray-900">{{ $membership->aadhar_card ?: 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Profile Image</p><p class="text-base font-medium text-gray-900">{{ $membership->image ? 'Uploaded' : 'Not provided' }}</p></div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Terms & Condition</p><p class="text-base font-medium text-gray-900">{{ $membership->terms_and_condition ?: 'Not provided' }}</p></div>
                    </div>
                </div>

                <!-- Account Status -->
                <div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-6">Account Status</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Verification Status</p>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold {{ $membership->isVerified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $membership->isVerified ? 'Verified' : 'Pending Verification' }}
                                        </span>
                                    </div>
                                    @if(!$membership->isVerified)
                                        <p class="text-xs text-gray-500 mt-2">Your account is under review</p>
                                    @endif
                                </div>
                                <div class="w-12 h-12 rounded-lg {{ $membership->isVerified ? 'bg-green-100' : 'bg-yellow-100' }} flex items-center justify-center">
                                    @if($membership->isVerified)
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Payment Status</p>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold {{ $membership->isPaid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $membership->isPaid ? 'Paid' : 'Unpaid' }}
                                        </span>
                                        @if(!$membership->isPaid)
                                            <a href="{{ route('membership.payment', $membership) }}" 
                                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                                Pay Now
                                            </a>
                                        @endif
                                    </div>
                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Payment Status</p><p class="text-sm font-medium text-gray-900">{{ $membership->payment_status ?: 'Not provided' }}</p></div>
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Transaction No</p><p class="text-sm font-medium text-gray-900">{{ $membership->transaction_no ?: 'N/A' }}</p></div>
                                    </div>
                                    @if(!$membership->isPaid)
                                        <p class="text-xs text-gray-500 mt-2">Complete payment to activate account</p>
                                    @endif
                                </div>
                                <div class="w-12 h-12 rounded-lg {{ $membership->isPaid ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center">
                                    @if($membership->isPaid)
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Referral & Sponsor</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Referral ID</p><p class="text-sm font-medium text-gray-900">{{ $membership->referal_id ?: 'N/A' }}</p></div>
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3"><p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Membership ID</p><p class="text-sm font-medium text-gray-900">{{ $membership->membership_id ?: 'N/A' }}</p></div>
                                    </div>
                                </div>
                                <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
