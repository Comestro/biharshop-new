<?php

namespace App\Livewire\Home\Component;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist as WishlistModel;

class Wishlist extends Component
{
    public $productId; 
    public $isWishlisted = false;

    public function mount($products) 
    {
        $this->productId = $products;

        if (Auth::check()) {
            $this->isWishlisted = WishlistModel::where('user_id', Auth::id())
                ->where('product_id', $this->productId)
                ->exists();
        }
    }

    public function wishlist()
    {
        if (!Auth::check()) {
            return; 
        }

        $existing = WishlistModel::where('user_id', Auth::id())
            ->where('product_id', $this->productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->isWishlisted = false;
        } else {
            WishlistModel::create([
                'user_id' => Auth::id(),
                'product_id' => $this->productId,
            ]);
            $this->isWishlisted = true;
        }
    }

    public function render()
    {
        return view('livewire.home.component.wishlist');
    }
}
