<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Layout('components.layouts.admin')]
class Products extends Component
{
    use WithPagination, WithFileUploads;

    #[Validate('required')] 
    public $name;
    #[Validate('required')] public $description;
    #[Validate('required|numeric')] public $price;
    #[Validate('required|numeric')] public $mrp;
    #[Validate('required|integer')] public $category_id;
    public $subcategory_id, $sku, $slug, $brand, $product_type, $tags;

    #[Validate('required')] public $color;
    #[Validate('required')] public $size;
    #[Validate('required')] public $material;
    public $in_stock;

    public $is_active = true;
    public $is_featured = false;
    public $is_new_arrival = false;
    public $is_on_sale = false;

    public $image;        
    public $existingImage;   

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $productId;

    
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

        $product = Product::findOrFail($id);

        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->subcategory_id = $product->subcategory_id;
        $this->sku = $product->sku;
        $this->slug = $product->slug;
        $this->brand = $product->brand;
        $this->product_type = $product->product_type;

        $this->color = $product->color;
        $this->size = $product->size;
        $this->material = $product->material;
        $this->in_stock = $product->in_stock;

        $this->mrp = $product->mrp;

        $this->existingImage = $product->image;

        $this->is_active = (bool)$product->is_active;
        $this->is_featured = (bool)$product->is_featured;
        $this->is_new_arrival = (bool)$product->is_new_arrival;
        $this->is_on_sale = (bool)$product->is_on_sale;
    }

   
    public function save()
    {
        $this->validate();

        $product = $this->editMode
            ? Product::findOrFail($this->productId)
            : new Product();

        // Basic
        $product->name = $this->name;
        $product->description = $this->description;
        $product->price = $this->price;
        $product->mrp = $this->mrp;
        $product->category_id = $this->category_id;
        $product->subcategory_id = $this->subcategory_id;
        $product->sku = $this->sku;
        $product->brand = $this->brand;
        $product->product_type = $this->product_type;
        $product->color = $this->color;
        $product->size = $this->size;
        $product->material = $this->material;
        $product->in_stock = $this->in_stock;

        // Flags
        $product->is_active = $this->is_active;
        $product->is_featured = $this->is_featured;
        $product->is_new_arrival = $this->is_new_arrival;
        $product->is_on_sale = $this->is_on_sale;

        // Slug generator
        $baseSlug = Str::slug($this->slug ?: $this->name);
        $exists = Product::where('slug', 'like', "$baseSlug%")
            ->when($this->editMode, fn($q) => $q->where('id', '!=', $this->productId))
            ->count();
        $product->slug = $exists ? "{$baseSlug}-{$exists}" : $baseSlug;

        // ✅ Handle image upload (single)
        if ($this->image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            if ($this->editMode && $product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $path = $this->image->store('products', 'public');
            $product->image = $path;
        }

        $product->save();

        // UI feedback
        session()->flash('message', $this->editMode ? 'Product updated successfully!' : 'Product added successfully!');

        $this->showModal = false;
        $this->resetForm();
    }

  
    public function delete($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        session()->flash('message', 'Product deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset([
            'name','description','price','mrp','category_id','subcategory_id','sku','slug','brand','product_type',
            'color','size','material','in_stock','is_active','is_featured','is_new_arrival','is_on_sale',
            'image','existingImage','editMode','productId'
        ]);

        $this->resetValidation();
    }

   
    public function render()
    {
        $products = Product::with('category')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.products', [
            'products' => $products,
            'categories' => Category::all(),
            'subcategories' => Category::all(),
        ]);
    }
}
