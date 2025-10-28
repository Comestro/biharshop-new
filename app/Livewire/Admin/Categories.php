<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Categories extends Component
{
    use WithPagination;

    public $name;
    public $description;
    public $parent_id = null; // ✅ new field
    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $categoryId;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable|min:10',
        'parent_id' => 'nullable|exists:categories,id'
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->categoryId = $id;
        $this->editMode = true;
        $this->showModal = true;

        $category = Category::findOrFail($id);
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
    }

    public function save()
    {
        $this->validate();

        $category = $this->editMode ? Category::findOrFail($this->categoryId) : new Category();

        $category->name = $this->name;
        $category->description = $this->description;
        $category->parent_id = $this->parent_id ?: null;
        $category->save();

        $this->showModal = false;
        $this->resetForm();

        session()->flash(
            'message',
            $this->editMode ? 'Category updated successfully!' : 'Category added successfully!'
        );
    }

    public function delete($id)
    {
        if (Product::where('category_id', $id)->exists()) {
            session()->flash('error', 'Cannot delete category with associated products');
            return;
        }

        if (Category::where('parent_id', $id)->exists()) {
            session()->flash('error', 'Cannot delete category with existing subcategories');
            return;
        }

        Category::destroy($id);
        session()->flash('message', 'Category deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'parent_id', 'editMode', 'categoryId']);
        $this->resetValidation();
    }

    public function render()
    {
        $categories = Category::withCount('products')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.categories', [
            'categories' => $categories,
            'allCategories' => Category::orderBy('name')->get() // ✅ for dropdown
        ]);
    }
}
