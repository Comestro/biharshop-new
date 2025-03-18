<div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex-shrink-0">
                    <span class="text-2xl font-bold text-black">BiharShop</span>
                </div>
                <div class="flex items-center space-x-4">
                    <input wire:model.live.debounce.300ms="search" 
                           type="search" 
                           placeholder="Search products..."
                           class="rounded-lg border-gray-300">
                    <a href="{{ route('membership.register') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Become a Member
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if($categories->count() > 0)
        <div class="flex space-x-4 mb-6 overflow-x-auto">
            @foreach($categories as $category)
                <button wire:click="$set('selectedCategory', {{ $category->id }})"
                        class="px-4 py-2 rounded-full {{ $selectedCategory == $category->id ? 'bg-indigo-600 text-white' : 'bg-white' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover rounded-t-lg">
                    @endif
                    <div class="p-4">
                        <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                        <p class="text-gray-600">₹{{ number_format($product->price, 2) }}</p>
                        <button class="mt-4 w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700">
                            Add to Cart
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No products found</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </main>
</div>
