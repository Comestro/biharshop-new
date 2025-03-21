<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;

class Homepage extends Component
{
    use WithPagination;

    public $selectedCategory = null;
    public $search = '';

    public function render()
    {
        $products = Product::query()
            ->when($this->selectedCategory, function($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('description', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(12);

        return view('livewire.home.homepage', [
            'products' => $products,
            'categories' => Category::all()
        ])->layout('components.layouts.app');
    }
}
