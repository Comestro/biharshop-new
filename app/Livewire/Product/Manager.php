<?php
namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;

class Manager extends Component
{
    use WithFileUploads;

    public $name;
    public $description;
    public $price;
    public $category_id;
    public $image;
    public $editing_id = null;

    protected $rules = [
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required|exists:categories,id',
        'image' => 'image|max:1024'
    ];

    public function save()
    {
        $this->validate();
        
        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products');
        }

        if ($this->editing_id) {
            Product::find($this->editing_id)->update($data);
        } else {
            Product::create($data);
        }

        $this->reset();
        session()->flash('message', 'Product saved successfully');
    }

    public function render()
    {
        return view('livewire.product.manager', [
            'products' => Product::latest()->paginate(10),
            'categories' => Category::all()
        ]);
    }
}
