<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Basic Information</h3>
            <dl class="grid grid-cols-1 gap-3">
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                    <dd class="text-sm text-gray-900">{{ $member->date_of_birth }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                    <dd class="text-sm text-gray-900">{{ ucfirst($member->gender) }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Nationality</dt>
                    <dd class="text-sm text-gray-900">{{ $member->nationality }}</dd>
                </div>
            </dl>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Contact Information</h3>
            <dl class="grid grid-cols-1 gap-3">
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Mobile</dt>
                    <dd class="text-sm text-gray-900">{{ $member->mobile }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900">{{ $member->email }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">WhatsApp</dt>
                    <dd class="text-sm text-gray-900">{{ $member->whatsapp ?: 'Not Provided' }}</dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="space-y-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Address</h3>
            <dl class="grid grid-cols-1 gap-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Home Address</dt>
                    <dd class="text-sm text-gray-900 mt-1">{{ $member->home_address }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">City</dt>
                    <dd class="text-sm text-gray-900">{{ $member->city }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">State</dt>
                    <dd class="text-sm text-gray-900">{{ $member->state }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">PIN Code</dt>
                    <dd class="text-sm text-gray-900">{{ $member->pincode }}</dd>
                </div>
            </dl>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Documents</h3>
            <dl class="grid grid-cols-1 gap-3">
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">PAN Card</dt>
                    <dd class="text-sm text-gray-900">{{ $member->pancard }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Aadhar Card</dt>
                    <dd class="text-sm text-gray-900">{{ $member->aadhar_card }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
