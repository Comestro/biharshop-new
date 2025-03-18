<?php
namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;

class Manager extends Component
{
    public $name;
    public $description;
    public $editing_id = null;

    protected $rules = [
        'name' => 'required|unique:categories,name',
        'description' => 'required'
    ];

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description
        ];

        if ($this->editing_id) {
            Category::find($this->editing_id)->update($data);
        } else {
            Category::create($data);
        }

        $this->reset();
        session()->flash('message', 'Category saved successfully');
    }

    public function render()
    {
        return view('livewire.category.manager', [
            'categories' => Category::latest()->paginate(10)
        ]);
    }
}
