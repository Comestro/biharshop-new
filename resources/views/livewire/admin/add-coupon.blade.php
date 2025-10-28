<div x-data="{ showModal: @entangle('showModal') }" class="p-0 lg:p-6">

    <div class="flex justify-between items-center mb-4">
        <input type="text" wire:model.live="search" placeholder="Search Coupon Code..."
            class="border rounded-md px-3 py-2 w-1/3 focus:ring-2 focus:ring-blue-500">
        <button wire:click="create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add
            Coupon</button>
    </div>

    @if (session()->has('message'))
        <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-left border">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3">Code</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Value</th>
                    <th class="p-3">Valid From</th>
                    <th class="p-3">Valid Until</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-medium">{{ $coupon->code }}</td>
                        <td class="p-3 capitalize">{{ $coupon->discount_type }}</td>
                        <td class="p-3">{{ $coupon->discount_value }}</td>
                        <td class="p-3">{{ $coupon->valid_from }}</td>
                        <td class="p-3">{{ $coupon->valid_until }}</td>
                        <td class="p-3">
                            @if ($coupon->status == "active")
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif

                        </td>
                        <td class="p-3 text-right space-x-2">
                            <button wire:click="edit({{ $coupon->id }})" class="text-blue-600 hover:underline">Edit</button>
                            <button wire:click="delete({{ $coupon->id }})" onclick="return confirm('Delete this coupon?')"
                                class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 p-4">No coupons found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-3">
            {{ $coupons->links() }}
        </div>
    </div>

    <!-- Coupon Modal -->
    <div x-show="showModal" x-cloak
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4 z-50" x-transition>
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl overflow-auto max-h-[90vh]"
            @click.away="showModal = false">
            <div class="flex justify-between items-center p-5 border-b">
                <h3 class="text-lg font-semibold">
                    {{ $editMode ? 'Edit Coupon' : 'Add Coupon' }}
                </h3>
                <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>

            <form wire:submit.prevent="save" class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Left -->
                    <div>
                        <label class="block text-sm font-medium">Code</label>
                        <input type="text" wire:model="code"
                            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        @error('code') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Discount Type</label>
                        <select wire:model="discount_type"
                            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">Select</option>
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed</option>
                        </select>
                        @error('discount_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Discount Value</label>
                        <input type="number" wire:model="discount_value" class="w-full border rounded px-3 py-2">
                        @error('discount_value') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Minimum Order Amount</label>
                        <input type="number" wire:model="min_order_amount" class="w-full border rounded px-3 py-2">
                        @error('min_order_amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Max Discount Amount</label>
                        <input type="number" wire:model="max_discount_amount" class="w-full border rounded px-3 py-2">
                        @error('max_discount_amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Usage Limit</label>
                        <input type="number" wire:model="usage_limit" class="w-full border rounded px-3 py-2">
                        @error('usage_limit') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Valid From</label>
                        <input type="date" wire:model="valid_from" class="w-full border rounded px-3 py-2">
                        @error('valid_from') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Valid Until</label>
                        <input type="date" wire:model="valid_until" class="w-full border rounded px-3 py-2">
                        @error('valid_until') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>


                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select wire:model="status"
                            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="active">active</option>
                            <option value="inactive">inactive</option>
                        </select>
                        @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ $editMode ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>