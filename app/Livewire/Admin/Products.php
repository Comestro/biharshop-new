<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class Products extends Component
{
    use WithPagination, WithFileUploads;

    public $name;
    public $description;
    public $price;
    public $image;
    public $category_id;
    public $existingImage;
    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $productId;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'required|min:10',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|max:1024', // 1MB Max
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->productId = $id;
        $this->editMode = true;
        $this->showModal = true;

        $product = Product::find($id);
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->existingImage = $product->image;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $product = Product::find($this->productId);
        } else {
            $product = new Product();
        }

        $product->name = $this->name;
        $product->description = $this->description;
        $product->price = $this->price;
        $product->category_id = $this->category_id;

        if ($this->image) {
            if ($product->image) {
                Storage::delete($product->image);
            }
            $product->image = $this->image->store('products');
        }

        $product->save();

        $this->showModal = false;
        $this->resetForm();
        session()->flash('message',
            $this->editMode ? 'Product updated successfully!' : 'Product added successfully!'
        );
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product->image) {
            Storage::delete($product->image);
        }
        $product->delete();
        session()->flash('message', 'Product deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'price', 'image', 'category_id', 'editMode', 'productId', 'existingImage']);
        $this->resetValidation();
    }

    public function render()
    {
        $products = Product::with('category')
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.products', [
            'products' => $products,
            'categories' => Category::all()
        ])->layout('components.layouts.admin');
    }
}
