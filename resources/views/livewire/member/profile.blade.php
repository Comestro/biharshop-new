<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                @php
                    $kycIncomplete = !($membership->father_name && $membership->mother_name && $membership->home_address && $membership->city && $membership->state && $membership->pincode && $membership->bank_name && $membership->account_no && $membership->ifsc && $membership->pancard && $membership->aadhar_card && $membership->image && $membership->terms_and_condition);
                @endphp

                @if($kycIncomplete)
                    <div class="mb-4 bg-blue-50 border-l-4 border-blue-500 p-4">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-8-3a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">Your KYC is incomplete. Please finish your profile to enable verification and payouts.</p>
                                <a href="{{ route('membership.register') }}" class="mt-2 inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Complete KYC</a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center space-x-6">
                    @if($membership->image)
                        <img src="{{ Storage::url($membership->image) }}" 
                             alt="Profile photo" 
                             class="h-24 w-24 rounded-full object-cover">
                    @else
                        <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-2xl text-gray-500">{{ substr($membership->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-2xl font-semibold">{{ $membership->name }}</h3>
                        <p class="text-gray-500">Member ID: {{ $membership->isVerified ? $membership->token : 'N/A' }}</p>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="mt-8">
                    <h4 class="text-lg font-medium mb-4">Personal Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-gray-900">{{ $membership->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Mobile</p>
                            <p class="text-gray-900">{{ $membership->mobile }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date of Birth</p>
                            <p class="text-gray-900">{{ $membership->date_of_birth }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Gender</p>
                            <p class="text-gray-900">{{ ucfirst($membership->gender) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bank Details -->
                <div class="mt-8">
                    <h4 class="text-lg font-medium mb-4">Bank Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Bank Name</p>
                            <p class="text-gray-900">{{ $membership->bank_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Account Number</p>
                            <p class="text-gray-900">{{ $membership->account_no }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">IFSC Code</p>
                            <p class="text-gray-900">{{ $membership->ifsc }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status Information -->
                <div class="mt-8">
                    <h4 class="text-lg font-medium mb-4">Account Status</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Verification Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $membership->isVerified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $membership->isVerified ? 'Verified' : 'Pending Verification' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Payment Status</p>
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $membership->isPaid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $membership->isPaid ? 'Paid' : 'Unpaid' }}
                                </span>
                                @if(!$membership->isPaid)
                                    <a href="{{ route('membership.payment', $membership) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700">
                                        Pay Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
