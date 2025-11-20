<div class="space-y-6">
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Binary Tree</h3>
        <div wire:ignore>
            <livewire:admin.binary-tree :root_id="$member->id" />
        </div>
    </div>
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Upline Details (Binary)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">Level</th>
                        <th class="px-3 py-2 border">Name</th>
                        <th class="px-3 py-2 border">Token</th>
                        <th class="px-3 py-2 border">Position</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($binaryUplines as $up)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border">{{ $up['level'] }}</td>
                            <td class="px-3 py-2 border">{{ $up['name'] }}</td>
                            <td class="px-3 py-2 border">{{ $up['token'] }}</td>
                            <td class="px-3 py-2 border">{{ ucfirst($up['position']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-400 py-3">No uplines</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Upline Details (Referral)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border">Level</th>
                        <th class="px-3 py-2 border">Name</th>
                        <th class="px-3 py-2 border">Token</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referralUplines as $up)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border">{{ $up['level'] }}</td>
                            <td class="px-3 py-2 border">{{ $up['name'] }}</td>
                            <td class="px-3 py-2 border">{{ $up['token'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-400 py-3">No uplines</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
