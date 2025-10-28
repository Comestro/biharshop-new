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
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        @if ($member->image)
                            <img src="{{ Storage::url($member->image) }}" alt="{{ $member->name }}"
                                class="h-24 w-24 rounded-full object-cover border-2 border-slate-200">
                        @else
                            <div
                                class="h-24 w-24 rounded-full bg-slate-100 flex items-center justify-center border-2 border-slate-200">
                                <span class="text-2xl font-medium text-slate-500">{{ substr($member->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="ml-6">
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

                    <!-- Team Size Summary - Redesigned -->
                    <div class="flex flex-row md:flex-col gap-3 md:gap-1 md:items-end">
                        <h3 class="hidden md:block text-sm font-medium text-slate-700 mb-2">Team Summary</h3>
                        <div class="flex items-center bg-slate-50 rounded-lg p-3 shadow-sm">
                            <div class="flex flex-col items-center px-4 border-r border-slate-200">
                                <span class="text-sm font-medium text-slate-500">Left Team</span>
                                <span class="text-2xl font-bold text-blue-600">{{ $leftTeamSize }}</span>
                            </div>
                            <div class="flex flex-col items-center px-4 border-r border-slate-200">
                                <span class="text-sm font-medium text-slate-500">Right Team</span>
                                <span class="text-2xl font-bold text-green-600">{{ $rightTeamSize }}</span>
                            </div>
                            <div class="flex flex-col items-center px-4">
                                <span class="text-sm font-medium text-slate-500">Total</span>
                                <span class="text-2xl font-bold text-slate-700">{{ $totalTeamSize }}</span>
                            </div>
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
                                            {{ ucfirst($member->binaryPosition->position) }}
                                        </dd>
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500">No position assigned yet</p>
                                @endif
                            </dl>
                        </div>

                        <!-- Team Size Information -->
                        <div class="bg-slate-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-slate-900 mb-3">Team Size</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                <div class="grid grid-cols-2">
                                    <dt class="text-sm font-medium text-slate-500">Left Team</dt>
                                    <dd class="text-sm text-slate-900">{{ $leftTeamSize }} members</dd>
                                </div>
                                <div class="grid grid-cols-2">
                                    <dt class="text-sm font-medium text-slate-500">Right Team</dt>
                                    <dd class="text-sm text-slate-900">{{ $rightTeamSize }} members</dd>
                                </div>
                                <div class="grid grid-cols-2">
                                    <dt class="text-sm font-medium text-slate-500">Total Team</dt>
                                    <dd class="text-sm text-slate-900">{{ $totalTeamSize }} members</dd>
                                </div>
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
            </div>
        </div>
    </div>
    <section id="tree">
        <div class="lg:p-7 mt-2">
            <livewire:admin.binary-tree :root_id="$member->id" :key="9" />
        </div>
    </section>

</div>