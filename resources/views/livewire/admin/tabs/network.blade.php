<div class="space-y-6">
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Network Position</h3>
        <dl class="grid grid-cols-1 gap-3">
            @if ($member->binaryPosition)
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Sponsor</dt>
                    <dd class="text-sm text-gray-900">{{ $member->binaryPosition->parent->name }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Position</dt>
                    <dd class="text-sm text-gray-900">{{ ucfirst($member->binaryPosition->position) }}</dd>
                </div>
            @else
                <p class="text-sm text-gray-500">No position assigned yet</p>
            @endif
        </dl>
    </div>
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Team Size</h3>
        <dl class="grid grid-cols-1 gap-3">
            <div class="grid grid-cols-2">
                <dt class="text-sm font-medium text-gray-500">Left Team</dt>
                <dd class="text-sm text-gray-900">{{ $leftTeamSize }} members</dd>
            </div>
            <div class="grid grid-cols-2">
                <dt class="text-sm font-medium text-gray-500">Right Team</dt>
                <dd class="text-sm text-gray-900">{{ $rightTeamSize }} members</dd>
            </div>
            <div class="grid grid-cols-2">
                <dt class="text-sm font-medium text-gray-500">Total Team</dt>
                <dd class="text-sm text-gray-900">{{ $totalTeamSize }} members</dd>
            </div>
        </dl>
    </div>
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Referral Information</h3>
        <dl class="grid grid-cols-1 gap-3">
            <div class="grid grid-cols-2">
                <dt class="text-sm font-medium text-gray-500">Referred By</dt>
                <dd class="text-sm text-gray-900">{{ $member->referrer ? $member->referrer->name : 'Direct' }}</dd>
            </div>
            <div class="grid grid-cols-2">
                <dt class="text-sm font-medium text-gray-500">Total Referrals</dt>
                <dd class="text-sm text-gray-900">{{ $member->referrals->count() }}</dd>
            </div>
        </dl>
    </div>
</div>
