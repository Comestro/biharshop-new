<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.members') }}" class="inline-flex items-center text-slate-600 hover:text-slate-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Members
            </a>
        </div>

        <!-- Member Header -->
        <div class="bg-white rounded-xl border border-slate-200 mb-6">
            <div class="p-6">
                <div class="flex items-center space-x-6">
                    @if ($member->image)
                        <img src="{{ Storage::url($member->image) }}" alt="{{ $member->name }}"
                            class="h-24 w-24 rounded-full object-cover border-2 border-slate-200">
                    @else
                        <div
                            class="h-24 w-24 rounded-full bg-slate-100 flex items-center justify-center border-2 border-slate-200">
                            <span class="text-2xl font-medium text-slate-500">{{ substr($member->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">{{ $member->name }}</h1>
                        <p class="text-sm text-slate-500">Member ID: {{ $member->token }}</p>
                        <div class="mt-2">
                            <span @class([
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                'bg-green-100 text-green-800' => $member->isVerified,
                                'bg-yellow-100 text-yellow-800' => !$member->isVerified && $member->isPaid,
                                'bg-slate-100 text-slate-800' => !$member->isPaid,
                            ])>
                                {{ $member->isVerified ? 'Verified' : ($member->isPaid ? 'Pending' : 'Unpaid') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-xl border border-slate-200">
            <!-- Mobile Tab Selector -->
            <div class="sm:hidden p-4 border-b border-slate-200">
                <select wire:model.live="activeTab"
                    class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="personal">Personal Information</option>
                    <option value="financial">Financial Details</option>
                    <option value="network">Network Details</option>
                    <option value="tree">Tree View</option>
                </select>
            </div>

            <!-- Desktop Tabs -->
            <div class="hidden sm:block border-b border-slate-200">
                <nav class="flex -mb-px">
                    <button wire:click="setTab('personal')" @class([
                        'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                        'border-blue-500 text-blue-600' => $activeTab === 'personal',
                        'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' =>
                            $activeTab !== 'personal',
                    ])>
                        Personal Information
                    </button>
                    <button wire:click="setTab('financial')" @class([
                        'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                        'border-blue-500 text-blue-600' => $activeTab === 'financial',
                        'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' =>
                            $activeTab !== 'financial',
                    ])>
                        Financial Details
                    </button>
                    <button wire:click="setTab('network')" @class([
                        'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                        'border-blue-500 text-blue-600' => $activeTab === 'network',
                        'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' =>
                            $activeTab !== 'network',
                    ])>
                        Network Details
                    </button>
                    <button wire:click="setTab('tree')" @class([
                        'px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap',
                        'border-blue-500 text-blue-600' => $activeTab === 'tree',
                        'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' =>
                            $activeTab !== 'tree',
                    ])>
                        Tree View
                    </button>
                </nav>
            </div>

            <div class="p-6">
                @if ($activeTab === 'personal')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div class="bg-slate-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-slate-900 mb-3">Basic Information</h3>
                                <dl class="grid grid-cols-1 gap-3">
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">Date of Birth</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->date_of_birth }}</dd>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">Gender</dt>
                                        <dd class="text-sm text-slate-900">{{ ucfirst($member->gender) }}</dd>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">Nationality</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->nationality }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="bg-slate-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-slate-900 mb-3">Contact Information</h3>
                                <dl class="grid grid-cols-1 gap-3">
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">Mobile</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->mobile }}</dd>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">Email</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->email }}</dd>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">WhatsApp</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->whatsapp ?: 'Not Provided' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-slate-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-slate-900 mb-3">Address</h3>
                                <dl class="grid grid-cols-1 gap-3">
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500">Home Address</dt>
                                        <dd class="text-sm text-slate-900 mt-1">{{ $member->home_address }}</dd>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">City</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->city }}</dd>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">State</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->state }}</dd>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">PIN Code</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->pincode }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="bg-slate-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-slate-900 mb-3">Documents</h3>
                                <dl class="grid grid-cols-1 gap-3">
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">PAN Card</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->pancard }}</dd>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">Aadhar Card</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->aadhar_card }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($activeTab === 'financial')
                    <div class="space-y-6">
                        <div class="bg-slate-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-slate-900 mb-3">Bank Account Details</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                <div class="grid grid-cols-2">
                                    <dt class="text-sm font-medium text-slate-500">Bank Name</dt>
                                    <dd class="text-sm text-slate-900">{{ $member->bank_name }}</dd>
                                </div>
                                <div class="grid grid-cols-2">
                                    <dt class="text-sm font-medium text-slate-500">Branch Name</dt>
                                    <dd class="text-sm text-slate-900">{{ $member->branch_name }}</dd>
                                </div>
                                <div class="grid grid-cols-2">
                                    <dt class="text-sm font-medium text-slate-500">Account Number</dt>
                                    <dd class="text-sm text-slate-900">{{ $member->account_no }}</dd>
                                </div>
                                <div class="grid grid-cols-2">
                                    <dt class="text-sm font-medium text-slate-500">IFSC Code</dt>
                                    <dd class="text-sm text-slate-900">{{ $member->ifsc }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-slate-900 mb-3">Payment Information</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                <div class="grid grid-cols-2">
                                    <dt class="text-sm font-medium text-slate-500">Payment Status</dt>
                                    <dd class="text-sm text-slate-900">{{ $member->isPaid ? 'Paid' : 'Unpaid' }}</dd>
                                </div>
                                @if ($member->isPaid)
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">Transaction ID</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->transaction_no }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                @endif

                @if ($activeTab === 'network')
                    <div class="space-y-6">
                        <div class="bg-slate-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-slate-900 mb-3">Network Position</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                @if ($member->binaryPosition)
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">Sponsor</dt>
                                        <dd class="text-sm text-slate-900">{{ $member->binaryPosition->parent->name }}
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-2">
                                        <dt class="text-sm font-medium text-slate-500">Position</dt>
                                        <dd class="text-sm text-slate-900">
                                            {{ ucfirst($member->binaryPosition->position) }}</dd>
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500">No position assigned yet</p>
                                @endif
                            </dl>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-slate-900 mb-3">Referral Information</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                <div class="grid grid-cols-2">
                                    <dt class="text-sm font-medium text-slate-500">Referred By</dt>
                                    <dd class="text-sm text-slate-900">
                                        {{ $member->referrer ? $member->referrer->name : 'Direct' }}
                                    </dd>
                                </div>
                                <div class="grid grid-cols-2">
                                    <dt class="text-sm font-medium text-slate-500">Total Referrals</dt>
                                    <dd class="text-sm text-slate-900">{{ $member->referrals->count() }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                @endif

                @if ($activeTab === 'tree')
                    <div class="space-y-6">
                        <div class="mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                                <div class="p-4 sm:p-6 border-b border-slate-200">
                                    <h2 class="text-lg sm:text-xl font-semibold text-slate-900">Binary Tree Structure</h2>
                                </div>

                                <div class="relative">
                                    <!-- Loading Overlay -->
                                    <div wire:loading class="absolute inset-0 bg-white/75 flex items-center justify-center z-10">
                                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-slate-200 border-t-blue-500"></div>
                                    </div>

                                    <!-- Tree Container -->
                                    <div class="p-4 sm:p-6 bg-slate-50/50">
                                        <div id="binary-tree-container"
                                             class="w-full h-[400px] sm:h-[500px] lg:h-[600px] rounded-lg bg-white"
                                             wire:ignore></div>
                                    </div>

                                    <!-- Zoom Controls -->
                                    <div class="absolute bottom-6 right-6 flex space-x-2">
                                        <button onclick="zoomIn()"
                                                class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50">
                                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                        </button>
                                        <button onclick="zoomOut()"
                                                class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50">
                                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                                            </svg>
                                        </button>
                                        <button onclick="resetZoom()"
                                                class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50">
                                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @push('scripts')
                        <script src="https://d3js.org/d3.v7.min.js"></script>

                    @endpush
                @endif

            </div>
        </div>
    </div>
</div>
@script
<script>

let svg, currentZoom, initialTransform;

function initBinaryTree(data) {
    if (!data) return;

    const container = document.getElementById('binary-tree-container');
    if (!container) return;

    // Clear previous tree
    d3.select("#binary-tree-container").html("");

    const width = container.offsetWidth;
    // ...rest of initBinaryTree function...
}

// Initialize when tab changes
    Livewire.on('tabChanged', ({ treeData }) => {
        console.log(treeData);
        if (treeData && treeData.length > 0) {
            setTimeout(() => initBinaryTree(treeData), 200);
        }
    });

    // Initialize if we start on tree tab
    if (@js($activeTab === 'tree')) {
        console.log('Tree Data:', @js($treeData));
        const treeData = @js($treeData);
        if (treeData && treeData.length > 0) {
            setTimeout(() => initBinaryTree(treeData), 200);
        }
    }
</script>
@endscript
