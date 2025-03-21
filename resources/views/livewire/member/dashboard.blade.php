<div>
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
                    <p class="text-2xl font-semibold">{{ $treeMembers }}</p>
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
