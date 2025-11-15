<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-xl font-medium text-gray-900">My Referrals</h2>
            <p class="mt-1 text-sm text-gray-500">Manage and track your direct referrals.</p>
        </div>
        @if ($membershipId != auth()->user()->membership->id)
            <div class="mb-6">
                <button wire:click="backLevel"
                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-md border bg-teal-700 text-white cursor-pointer">
                    <- Back </span>
            </div>

        @endif

        <!-- Table Container -->
        <div class="border rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Member ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Joined Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                View Next
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($referrals as $referral)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $referral->member->token }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $referral->member->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $referral->member->email }}</div>
                                    <div>{{ $referral->member->mobile }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $referral->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span @class([
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        'bg-green-100 text-green-800' => $referral->isVerified,
                                        'bg-yellow-100 text-yellow-800' => !$referral->isVerified && $referral->isPaid,
                                        'bg-gray-100 text-gray-800' => !$referral->isPaid,
                                    ])>
                                        {{ $referral->isVerified ? 'Active' : ($referral->isPaid ? 'Pending' : 'Incomplete') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="nextLevel({{ $referral->member->id }})"
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-md border bg-teal-700 text-white cursor-pointer">
                                        Next ->
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No referrals found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($referrals->hasPages())
                <div class="px-4 py-3 border-t">
                    {{ $referrals->links() }}
                </div>
            @endif
        </div>
    </div>
</div>