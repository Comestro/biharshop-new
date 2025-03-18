<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        @if(!$membership->isVerified)
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Application Under Review</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Your application is currently being reviewed by our team. This process typically takes 24-48 hours.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Membership Details</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Application ID: {{ $membership->token }}</p>
            </div>
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
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

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $membership->date_of_birth }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $membership->home_address }}<br>
                            {{ $membership->city }}, {{ $membership->state }} - {{ $membership->pincode }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bank Details</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $membership->bank_name }}<br>
                            Account: {{ $membership->account_no }}<br>
                            IFSC: {{ $membership->ifsc }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nominee Details</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $membership->nominee_name }}<br>
                            Relation: {{ $membership->nominee_relation }}
                        </dd>
                    </div>

                    @if($membership->image)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Profile Photo</dt>
                            <dd class="mt-1">
                                <img src="{{ Storage::url($membership->image) }}" 
                                     alt="Profile Photo" 
                                     class="h-32 w-32 object-cover rounded-lg">
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Submitted on {{ $membership->updated_at->format('d M Y, h:i A') }}
                    </div>
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $membership->isVerified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $membership->isVerified ? 'Verified' : 'Under Review' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
