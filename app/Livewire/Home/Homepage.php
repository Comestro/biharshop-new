<?php

namespace App\Livewire\Home;

use App\Models\OrderItem;
use Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;
#[Layout('components.layouts.app')]
class Homepage extends Component
{
    use WithPagination;

    public $selectedCategory = null;
    public $search = '';


    public function addToCart($pId)
    {
        $product = Product::findOrFail($pId);
        if (!$product)
            return;
        OrderItem::create([
            'user_id' => Auth::id(),
            'product_id' => $pId,
            'unit_price' => $product->price,
            'qty' => 1,
            'subtotal' => $product->price,
        ]);
    }
    public function render()
    {
        $products = Product::query()
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(12);

        return view('livewire.home.homepage', [
            'products' => $products,
            'categories' => Category::all()
        ]);
    }
}
