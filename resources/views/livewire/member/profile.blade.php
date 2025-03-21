<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
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
                        <p class="text-gray-500">Member ID: {{ $membership->token }}</p>
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $membership->isPaid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $membership->isPaid ? 'Paid' : 'Unpaid' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
