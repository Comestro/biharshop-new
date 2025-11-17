<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Status Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900">Membership Status</h2>
                
                <div class="mt-6 space-y-6">
                    <!-- Payment Status -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($membership->isPaid)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Payment Received
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Payment Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Member Details -->
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membership->token }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membership->created_at->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membership->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $membership->email }}</dd>
                        </div>
                    </dl>

                    <!-- Verification Status -->
                    <div class="rounded-md p-4 {{ $membership->isVerified ? 'bg-green-50' : 'bg-yellow-50' }}">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @if($membership->isVerified)
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium {{ $membership->isVerified ? 'text-green-800' : 'text-yellow-800' }}">
                                    {{ $membership->isVerified ? 'Account Verified' : 'Verification Pending' }}
                                </h3>
                                <div class="mt-2 text-sm {{ $membership->isVerified ? 'text-green-700' : 'text-yellow-700' }}">
                                    {{ $membership->isVerified 
                                        ? 'Your membership has been verified. You can now access all features.' 
                                        : 'Your account is under review. This usually takes 24-48 hours.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        

        <!-- Rest of dashboard content -->
    </div>
</div>
