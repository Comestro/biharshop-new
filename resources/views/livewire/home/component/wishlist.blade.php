<div>
    @auth
        <button wire:click="wishlist"
            class="w-12 h-11 flex items-center justify-center rounded-lg transition  {{ $isWishlisted ? 'bg-red-100 text-red-500 hover:bg-red-200' : 'bg-gray-200 text-gray-500 hover:bg-gray-300' }}">
            @if ($isWishlisted)
                <i class="fas fa-heart"></i>
            @else
                <i class="far fa-heart"></i>
            @endif
        </button>
    @else
        <a href="{{ route('login') }}"
            class="w-12 h-11 flex items-center justify-center rounded-lg transition  {{ $isWishlisted ? 'bg-red-100 text-red-500 hover:bg-red-200' : 'bg-gray-200 text-gray-500 hover:bg-gray-300' }}">
            <i class="far fa-heart"></i>
        </a>
    @endauth
</div>