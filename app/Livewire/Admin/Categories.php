<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class Categories extends Component
{
    use WithPagination;

    public $name;
    public $description;
    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $categoryId;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable|min:10'
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

        $category = Category::find($id);
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $category = Category::find($this->categoryId);
        } else {
            $category = new Category();
        }

        $category->name = $this->name;
        $category->description = $this->description;
        $category->save();

        $this->showModal = false;
        $this->resetForm();
        session()->flash('message',
            $this->editMode ? 'Category updated successfully!' : 'Category added successfully!'
        );
    }

    public function delete($id)
    {
        if (Product::where('category_id', $id)->exists()) {
            session()->flash('error', 'Cannot delete category with associated products');
            return;
        }
        Category::destroy($id);
        session()->flash('message', 'Category deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'editMode', 'categoryId']);
        $this->resetValidation();
    }

    public function render()
    {
        $categories = Category::withCount('products')
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.categories', [
            'categories' => $categories
        ])->layout('components.layouts.admin');
    }
}
