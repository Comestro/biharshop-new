<?php

namespace App\Livewire\Home;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ProductView extends Component
{
    public $product;
    public $bigimage;
    public $relatedProducts = [];


    // Mount method me route parameter milta hai
    public function mount($slug)
    {
        $this->product = Product::where('slug', $slug)->firstOrFail();
        $this->bigimage = $this->product->image;
        $this->relatedProducts = Product::where('category_id',$this->product->category_id)->get();

    }
    public function changeImage($imagename){
        $this->bigimage = $imagename;
    }
    public function render()
    {
        return view('livewire.home.product-view', [
            'product' => $this->product
        ]);
    }
}
