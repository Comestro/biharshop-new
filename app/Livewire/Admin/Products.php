<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
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

    // Basic
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $subcategory_id;
    public $sku;
    public $slug;
    public $brand;
    public $product_type;
    public $tags;

    // Variants
    public $color;
    public $size;
    public $material;

    // Pricing
    public $mrp;
    public $discount;

    // Images
    public $image;
    public $existingImage;
    public $images = [];
    public $existingImages = [];

    // Flags
    public $is_active = true;
    public $is_featured = false;
    public $is_new_arrival = false;
    public $is_on_sale = false;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $productId;

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'description' => 'required|min:10',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'slug' => 'nullable|unique:products,slug',
            'image' => 'required|image|max:1024',
            'images.*' => 'nullable|image|max:1024',
            'mrp' => 'required|numeric|min:0',
        ];
    }

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

        // Basic
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->sku = $product->sku;
        $this->slug = $product->slug;
        $this->brand = $product->brand;
        $this->product_type = $product->product_type;

        // Variants
        $this->color = $product->color;
        $this->size = $product->size ? json_encode($product->size) : null;
        $this->material = $product->material;

        // Pricing
        $this->mrp = $product->mrp;

        // Images
        $this->existingImage = $product->image;
        $this->existingImages = [
            $product->image ?? null,
            $product->image1 ?? null,
            $product->image2 ?? null,
            $product->image3 ?? null,
        ];

        // Flags
        $this->is_active = $product->is_active;
        $this->is_featured = $product->is_featured;
        $this->is_new_arrival = $product->is_new_arrival;
        $this->is_on_sale = $product->is_on_sale;
    }

    public function save()
    {

        $product = $this->editMode ? Product::find($this->productId) : new Product();

        // Basic
        $product->name = $this->name;
        $slug = Str::slug($this->name);
        $count = Product::where('slug', 'like', "{$slug}%")
            ->when($this->editMode, fn($q) => $q->where('id', '!=', $this->productId))
            ->count();
        $product->slug = $count ? "{$slug}-{$count}" : $slug;
        $product->description = $this->description;
        $product->price = $this->price;
        $product->category_id = $this->category_id;
        $product->subcategory_id = $this->category_id;
        $product->sku = $this->sku;
        $product->slug = $this->slug;
        $product->brand = $this->brand;
        $product->product_type = $this->product_type;

        // Variants
        $product->color = $this->color;
        $product->size = $this->size ? json_decode($this->size, true) : [];
        $product->material = $this->material;

        // Pricing
        $product->mrp = $this->mrp;

        // Flags
        $product->is_active = $this->is_active;
        $product->is_featured = $this->is_featured;
        $product->is_new_arrival = $this->is_new_arrival;
        $product->is_on_sale = $this->is_on_sale;

        // Multiple images
        foreach ($this->images as $index => $img) {
            if ($img) {
                $filename = $img->store('products', 'public');
                switch ($index) {
                    case 0:
                        $product->image = $filename;
                        break;
                    case 1:
                        $product->image1 = $filename;
                        break;
                    case 2:
                        $product->image2 = $filename;
                        break;
                    case 3:
                        $product->image3 = $filename;
                        break;
                }
            }
        }

        $product->save();

        $this->showModal = false;
        $this->resetForm();
        session()->flash('message', $this->editMode ? 'Product updated successfully!' : 'Product added successfully!');
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product->image) {
            Storage::delete($product->image);
        }
        // Delete multiple images
        foreach (['image_id', 'image1', 'image2', 'image3'] as $imgField) {
            if ($product->$imgField)
                Storage::delete($product->$imgField);
        }
        $product->delete();
        session()->flash('message', 'Product deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'description',
            'price',
            'category_id',
            'subcategory_id',
            'sku',
            'slug',
            'brand',
            'product_type',
            'tags',
            'color',
            'size',
            'material',
            'mrp',
            'discount',
            'image',
            'images',
            'existingImage',
            'existingImages',
            'is_active',
            'is_featured',
            'is_new_arrival',
            'is_on_sale',
            'editMode',
            'productId'
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        $products = Product::with('category')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.products', [
            'products' => $products,
            'categories' => Category::all(),
            'subcategories' => Category::all(), // for subcategory select
        ]);
    }
}
