<div class="w-full">
    <!-- Title and Button -->
    <div class="flex justify-between items-center flex-wrap gap-3 mb-3">
        <h1 class="text-2xl font-bold text-gray-800">Plans Management</h1>
        <button wire:click="$set('planModal', true)"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full sm:w-auto">Add New Plan</button>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
        <h2 class="text-lg font-semibold mb-3">All Plans</h2>

        <table class="min-w-full text-sm border border-slate-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 border border-slate-200">Image</th>
                    <th class="px-3 py-2 border border-slate-200">Name</th>
                    <th class="px-3 py-2 border border-slate-200">Price</th>
                    <th class="px-3 py-2 border border-slate-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 border border-slate-200 text-center">
                            <img src="{{ asset('storage/' . $plan->image) }}"
                                class="w-10 h-10 mx-auto rounded object-cover">
                        </td>
                        <td class="px-3 py-2 border border-slate-200 font-medium text-gray-800">{{ $plan->name }}</td>
                        <td class="px-3 py-2 border border-slate-200 text-gray-600">₹{{ number_format($plan->price, 2) }}</td>
                        <td class="px-3 py-2 border border-slate-200 text-center">
                            <button wire:click="edit({{ $plan->id }})"
                                class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
                            <button wire:click="delete({{ $plan->id }})"
                                class="bg-red-600 text-white px-3 py-1 rounded ml-2">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-400 py-3">No plans yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if($planModal)
        <div class="fixed inset-0 bg-black/20 flex justify-center items-center z-50 ">
            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg overflow-y-auto max-h-full p-6 relative">
                <!-- Close Button -->
                <button wire:click="$set('planModal', false)"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>

                <h2 class="text-lg font-semibold mb-4">{{ $editMode ? 'Edit Plan' : 'Add New Plan' }}</h2>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="text-sm font-semibold">Name</label>
                        <input type="text" wire:model="name" class="w-full border border-slate-200 rounded p-2" placeholder="Plan Name">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Type</label>
                        <select wire:model="type" class="w-full border border-slate-200 rounded p-2">
                            <option value="main">Main</option>
                            <option value="add_on">Add On</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Price (₹)</label>
                        <input type="number" step="0.01" wire:model="price" class="w-full border border-slate-200 rounded p-2"
                            placeholder="Plan Price">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Description</label>
                        <textarea wire:model="description" rows="2" class="w-full border border-slate-200 rounded p-2"></textarea>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Features</label>
                        <textarea wire:model="features" rows="2" class="w-full border border-slate-200 rounded p-2"></textarea>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Image</label>
                        <input type="file" wire:model="image" class="w-full border border-slate-200 rounded p-2">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" class="mt-3 w-24 h-24 rounded-md object-cover">
                        @endif
                    </div>

                    <div class="flex justify-end gap-3 mt-2">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" wire:click="save"
                            class="bg-blue-600 text-white px-4 py-2 rounded">{{ $editMode ? 'Update Plan' : 'Create Plan' }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>