<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Income Report</h2>

            <div class="flex gap-2">
                <button wire:click="$set('activeTab','monthly')" class="px-4 py-2 text-sm rounded-md 
                    {{ $activeTab === 'monthly' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Monthly
                </button>

                <button wire:click="$set('activeTab','daily')" class="px-4 py-2 text-sm rounded-md 
                    {{ $activeTab === 'daily' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Daily
                </button>
            </div>
        </div>

        <!-- MONTHLY TABLE -->
        @if($activeTab === 'monthly')
            <div class="w-full overflow-x-auto border rounded-lg">
                <table class="min-w-max w-full table-auto divide-y divide-gray-200">

                    <!-- Head -->
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach(['SNo', 'Payout Date', 'Everest Income', 'Direct Sponsor Income', 'Matching Income', 'Daily Income', 'Gross Income', 'TDS Amount', 'Admin Charge', 'Re-topup', 'Total Deduction', 'Previous Income', 'Net Income', 'Closing Income', 'Statement'] as $head)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ $head }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <!-- Body -->
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($monthly as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">{{ $row['sno'] }}</td>
                                <td class="px-4 py-3 text-sm">{{ $row['payout_date'] }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['everest'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['direct_sponsor'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['matching'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['daily'], 2) }}</td>

                                <td class="px-4 py-3 text-sm font-semibold text-green-700">
                                    ₹{{ number_format($row['gross'], 2) }}
                                </td>

                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['tds'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['admin'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['retopup'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['total_deduction'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['previous_income'], 2) }}</td>

                                <td class="px-4 py-3 text-sm font-semibold text-indigo-700">
                                    ₹{{ number_format($row['net_income'], 2) }}
                                </td>

                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['closing_income'], 2) }}</td>

                                <td class="px-4 py-3 text-sm">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800">Statement</a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="16" class="px-4 py-8 text-center text-gray-400">No records</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        @endif

        <!-- DAILY TABLE -->
        @if($activeTab === 'daily')
            <div class="w-full overflow-x-auto border rounded-lg mt-6">
                <table class="min-w-max w-full table-auto divide-y divide-gray-200">

                    <!-- Head -->
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach(['SNo', 'Payout Date', 'Everest Income', 'Direct Sponsor Income', 'Matching Income', 'Diamond Club', 'Daily Income', 'Gross Income', 'TDS Amount', 'Admin Charge', 'Retopup Deduction', 'Total Deduction', 'Net Income'] as $head)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ $head }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <!-- Body -->
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($daily as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">{{ $row['sno'] }}</td>
                                <td class="px-4 py-3 text-sm">{{ $row['payout_date'] }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['everest'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['direct_sponsor'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['matching'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['diamond'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['daily'], 2) }}</td>

                                <td class="px-4 py-3 text-sm font-semibold text-green-700">
                                    ₹{{ number_format($row['gross'], 2) }}
                                </td>

                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['tds'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['admin'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['retopup'], 2) }}</td>
                                <td class="px-4 py-3 text-sm">₹{{ number_format($row['total_deduction'], 2) }}</td>

                                <td class="px-4 py-3 text-sm font-semibold text-indigo-700">
                                    ₹{{ number_format($row['net_income'], 2) }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="13" class="px-4 py-8 text-center text-gray-400">No records</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        @endif

    </div>
</div>