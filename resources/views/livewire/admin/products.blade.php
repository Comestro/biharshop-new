<div>
    <div class="py-12 bg-gray-50" x-data="{ showModal: @entangle('showModal') }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session()->has('message'))
                <div class="mb-4 mx-4 sm:mx-0 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                        <div class="flex items-center">
                            <h2 class="text-xl font-semibold text-gray-900">Products</h2>
                            <button wire:click="create"
                                class="ml-4 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                Add Product
                            </button>
                        </div>
                        <div class="relative">
                            <input type="search" wire:model.live.debounce.300ms="search"
                                placeholder="Search products..."
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                class="h-12 w-12 object-cover rounded-lg">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $product->description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₹{{ number_format($product->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button wire:click="edit({{ $product->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </button>
                                        <button wire:click="delete({{ $product->id }})"
                                            wire:confirm="Are you sure you want to delete this product?"
                                            class="text-red-600 hover:text-red-900">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No products found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $products->links() }}
                </div>
            </div>
        </div>

        <!-- Product Modal -->
        <div x-show="showModal" x-cloak
            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4" x-transition>
            <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                @click.away="showModal = false">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $editMode ? 'Edit Product' : 'Add Product' }}
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product Name *</label>
                            <input type="text" wire:model.live="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 border">
                            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="hidden">
                            <label class="block text-sm font-medium text-gray-700">SKU</label>
                            <input type="text" wire:model.live="sku"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="(optional)">
                            @error('sku') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Brand Name *</label>
                            <input type="text" wire:model.live="brand"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Dabur,Link,Nike,Doller">
                            @error('brand') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category *</label>
                            <select wire:model.live="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Subcategory</label>
                            <select wire:model.live="subcategory_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Subcategory</option>

                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product Type</label>
                            <input type="text" wire:model.live="product_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="electronic , faishon">
                            @error('product_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea wire:model.live="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 border"></textarea>
                        @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pricing -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price (₹) *</label>
                            <input type="number" wire:model.live="price" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
                            @error('price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">MRP (₹) *</label>
                            <input type="number" wire:model.live="mrp" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
                            @error('mrp') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Discount (%)</label>
                            <input type="number" wire:model.live="discount" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
                            @error('discount') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Variants -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Color *</label>
                            <input type="text" wire:model.live="color"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
                            @error('color') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Size </label>
                            <input type="text" wire:model.live="size" placeholder='["S","M","L","38","32"]'
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
                            @error('size') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Material *</label>
                            <input type="text" wire:model.live="material"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
                            @error('material') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">In Stock *</label>
                            <input type="text" wire:model.live="in_stock"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
                            @error('in_stock') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" wire:model.live="image" class="mt-1 block w-full">

                            <!-- 🖼️ Preview new image -->
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="mt-2 h-24 rounded-md object-cover"
                                    alt="Preview">
                            @elseif ($existingImage)
                                <img src="{{ asset('storage/' . $existingImage) }}"
                                    class="mt-2 h-24 rounded-md object-cover" alt="Existing Image">
                            @endif

                            @error('image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>


                    <!-- Flags -->
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <label><input type="checkbox" wire:model.live="is_active"> Active</label>
                        @error('is_active') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        <label><input type="checkbox" wire:model.live="is_featured"> Featured</label>
                        @error('is_featured') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        <label><input type="checkbox" wire:model.live="is_new_arrival"> New Arrival</label>
                        @error('is_new_arrival') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        <label><input type="checkbox" wire:model.live="is_on_sale"> On Sale</label>
                        @error('is_on_sale') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                            {{ $editMode ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

    <x-global.loader wire:loading.flex wire:target="save" message="Saving product..." />
    <x-global.loader wire:loading.flex wire:target="delete" message="Deleting product..." />
    <x-global.loader wire:loading.flex wire:target="image" message="Uploading image..." />
</div>