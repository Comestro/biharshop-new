<div class="space-y-6">
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Bank Account Details</h3>
        <dl class="grid grid-cols-1 gap-3">
            <div class="grid grid-cols-2">
                <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                <dd class="text-sm text-gray-900">{{ $member->bank_name }}</dd>
            </div>
            <div class="grid grid-cols-2">
                <dt class="text-sm font-medium text-gray-500">Branch Name</dt>
                <dd class="text-sm text-gray-900">{{ $member->branch_name }}</dd>
            </div>
            <div class="grid grid-cols-2">
                <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                <dd class="text-sm text-gray-900">{{ $member->account_no }}</dd>
            </div>
            <div class="grid grid-cols-2">
                <dt class="text-sm font-medium text-gray-500">IFSC Code</dt>
                <dd class="text-sm text-gray-900">{{ $member->ifsc }}</dd>
            </div>
        </dl>
    </div>
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Payment Information</h3>
        <dl class="grid grid-cols-1 gap-3">
            <div class="grid grid-cols-2">
                <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                <dd class="text-sm text-gray-900">{{ $member->isPaid ? 'Paid' : 'Unpaid' }}</dd>
            </div>
            @if ($member->isPaid)
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                    <dd class="text-sm text-gray-900">{{ $member->transaction_no }}</dd>
                </div>
            @endif
        </dl>
    </div>
</div>
