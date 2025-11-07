<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div
        class="max-w-6xl mx-auto bg-white rounded-xl shadow border border-gray-200 flex flex-col md:flex-row overflow-hidden">

        {{-- Sidebar (hidden on mobile) --}}
        <livewire:user.component.sidebar />


        {{-- Main Content --}}
        <main class="flex-1 p-6 bg-gray-50 overflow-y-auto">
            <div class="max-w-5xl mx-auto space-y-6">

                <!-- Page Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">My Addresses</h2>
                    <button wire:click="openAddModel"
                        class="bg-teal-700 text-white text-sm px-4 py-2 rounded-lg hover:bg-teal-800 transition">
                        <i class="fa-solid fa-plus mr-1"></i> Add New
                    </button>
                </div>

                <!-- Addresses -->
                @forelse ($addresses as $address)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $address->city }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed mt-1">
                                    {{ $address->address_line1 }}<br>
                                    {{ $address->state }} - {{ $address->postal_code }}<br>
                                    {{ $address->country }}<br>
                                    Phone: {{ $address->phone }}
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-3 justify-end mt-3">
                            <button wire:click="openEditModel({{ $address->id }})"
                                class="text-sm text-teal-700 font-medium hover:underline">
                                <i class="fa-solid fa-pen mr-1"></i> Edit
                            </button>
                            <button wire:click="deleteAddress({{ $address->id }})"
                                class="text-sm text-red-600 font-medium hover:underline">
                                <i class="fa-solid fa-trash mr-1"></i> Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-6">No address found.</p>
                @endforelse
            </div>
        </main>
    </div>

    {{-- Add/Edit Modal --}}
    @if ($isOpenModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6 relative">
                <button wire:click="closeModal"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                <h2 class="text-2xl font-semibold mb-5 text-gray-800">
                    {{ $editMode ? 'Edit Address' : 'Add New Address' }}
                </h2>

                <form wire:submit.prevent="{{ $editMode ? 'updateAddress' : 'addAddress' }}">
                    <div class="space-y-4">
                        <input type="text" wire:model="address_line1" placeholder="Address Line 1"
                            class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500">
                        <input type="text" wire:model="near_by" placeholder="Near By (optional)"
                            class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500">

                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" wire:model="city" placeholder="City"
                                class="border rounded-lg p-2 focus:ring-2 focus:ring-teal-500">
                            <input type="text" wire:model="state" placeholder="State"
                                class="border rounded-lg p-2 focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" wire:model="country" placeholder="Country"
                                class="border rounded-lg p-2 focus:ring-2 focus:ring-teal-500">
                            <input type="text" wire:model="postal_code" placeholder="Postal Code"
                                class="border rounded-lg p-2 focus:ring-2 focus:ring-teal-500">
                        </div>

                        <input type="text" wire:model="phone" placeholder="Phone"
                            class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500">

                        <button type="submit"
                            class="w-full bg-teal-600 text-white rounded-lg py-2 font-semibold hover:bg-teal-700 transition">
                            {{ $editMode ? 'Update Address' : 'Save Address' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>