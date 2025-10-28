<div>
    <div class="py-12 bg-gray-50" x-data="{ showModal: @entangle('showModal') }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session()->has('message'))
                <div class="mb-4 mx-4 sm:mx-0 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            @if(session()->has('error'))
                <div class="mb-4 mx-4 sm:mx-0 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                        <div class="flex items-center">
                            <h2 class="text-xl font-semibold text-gray-900">Categories</h2>
                            <button wire:click="create"
                                class="ml-4 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                Add Category
                            </button>
                        </div>
                        <div class="relative">
                            <input type="search" wire:model.live.debounce.300ms="search"
                                placeholder="Search categories..."
                                class="block w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10 border px-3 py-2">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-md truncate">
                                        {{ $category->description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $category->products_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button wire:click="edit({{ $category->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </button>
                                        <button wire:click="delete({{ $category->id }})"
                                            wire:confirm="Are you sure you want to delete this category?"
                                            class="text-red-600 hover:text-red-900">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        No categories found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>

        <!-- Category Modal -->
        <div x-show="showModal" x-cloak
            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4" x-transition>
            <div class="bg-white rounded-xl max-w-lg w-full" @click.away="showModal = false">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $editMode ? 'Edit Category' : 'Add Category' }}
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category Name</label>
                            <input type="text" wire:model="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                            @error('name')
                                <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Parent Category</label>
                            <select wire:model="parent_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                                <option value="">-- None (Main Category) --</option>
                                @foreach ($allCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2"></textarea>
                            @error('description')
                                <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                            {{ $editMode ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <x-global.loader wire:loading.flex wire:target="save" message="Saving category..." />
    <x-global.loader wire:loading.flex wire:target="delete" message="Deleting category..." />
</div>