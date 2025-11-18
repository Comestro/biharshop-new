<div class="p-6 bg-white shadow rounded-xl">

    <!-- Header -->
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-bold text-gray-800">Referral List</h2>

        <select wire:model.live="level" class="px-3 py-2 border rounded-lg bg-white text-sm focus:ring focus:ring-blue-300">
            <option value="1">Level 1</option>
            <option value="2">Level 2</option>
            <option value="3">Level 3</option>
            <option value="4">Level 4</option>
            <option value="5">Level 5</option>
        </select>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto border rounded-xl">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr class="text-gray-700">
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Token</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Joined</th>
                </tr>
            </thead>

            <tbody>
                @forelse($referrals as $row)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $row->name }}</td>
                        <td class="px-4 py-2">{{ $row->token }}</td>
                        <td class="px-4 py-2">{{ $row->email }}</td>
                        <td class="px-4 py-2">{{ $row->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                            No referrals found for Level {{ $level }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>