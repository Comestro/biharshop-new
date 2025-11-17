<div class="py-6 overflow-x-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Income Report</h2>
            <div class="flex gap-2">
                <button wire:click="$set('activeTab','monthly')" class="px-4 py-2 text-sm rounded-md {{ $activeTab==='monthly' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">Monthly</button>
                <button wire:click="$set('activeTab','daily')" class="px-4 py-2 text-sm rounded-md {{ $activeTab==='daily' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">Daily</button>
            </div>
        </div>

        @if($activeTab==='monthly')
            <div class="border rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SNo</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Payout Date</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Everest Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Direct Sponsor Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Matching Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diamond Club</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Daily Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Gross Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">TDS Amount</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Admin Charge</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Retopup Deduction</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Deduction</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Previous Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Net Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Closing Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Statement</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($monthly as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 text-sm">{{ $row['sno'] }}</td>
                            <td class="px-3 py-3 text-sm">{{ $row['payout_date'] }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['everest'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['direct_sponsor'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['matching'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['diamond'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['daily'], 2) }}</td>
                            <td class="px-3 py-3 text-sm font-semibold text-green-700">₹{{ number_format($row['gross'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['tds'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['admin'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['retopup'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['total_deduction'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['previous_income'], 2) }}</td>
                            <td class="px-3 py-3 text-sm font-semibold text-indigo-700">₹{{ number_format($row['net_income'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['closing_income'], 2) }}</td>
                            <td class="px-3 py-3 text-sm"><a href="#" class="text-indigo-600 hover:text-indigo-800">Statement</a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="16" class="px-3 py-8 text-center text-gray-400">No records</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if($activeTab==='daily')
            <div class="border rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SNo</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Payout Date</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Everest Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Direct Sponsor Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Matching Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diamond Club</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Daily Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Gross Income</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">TDS Amount</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Admin Charge</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Retopup Deduction</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Deduction</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Net Income</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($daily as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 text-sm">{{ $row['sno'] }}</td>
                            <td class="px-3 py-3 text-sm">{{ $row['payout_date'] }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['everest'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['direct_sponsor'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['matching'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['diamond'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['daily'], 2) }}</td>
                            <td class="px-3 py-3 text-sm font-semibold text-green-700">₹{{ number_format($row['gross'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['tds'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['admin'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['retopup'], 2) }}</td>
                            <td class="px-3 py-3 text-sm">₹{{ number_format($row['total_deduction'], 2) }}</td>
                            <td class="px-3 py-3 text-sm font-semibold text-indigo-700">₹{{ number_format($row['net_income'], 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="px-3 py-8 text-center text-gray-400">No records</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

