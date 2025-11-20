<?php

namespace App\Livewire\Admin;


use App\Models\Plan;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
#[Layout('components.layouts.admin')]
class ManagePlan extends Component
{
    use WithFileUploads;
    public $plans;
    public $planId;
    public $type = 'main';
    public $name, $price, $description, $features, $image;
    public $editMode = false;
    public $planModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'required|string',
        'features' => 'required|string',
        'image' => 'nullable|image',
        'type' => 'required|in:main,add_on',
    ];

    public function mount()
    {
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $this->plans = Plan::latest()->get();
    }

    public function resetFields()
    {
        $this->reset(['name', 'price', 'description', 'features', 'image', 'planId', 'editMode', 'type']);
    }

    public function save()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('plans', 'public');
        }

        if ($this->editMode) {
            $plan = Plan::find($this->planId);

            if (!$plan)
                return;

            if ($this->image && $plan->image) {
                Storage::disk('public')->delete($plan->image);
            }

            $plan->update([
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'features' => $this->features,
                'image' => $imagePath ?? $plan->image,
                'type' => $this->type,
            ]);
            $this->planModal = false;

            session()->flash('success', 'Plan updated successfully!');
        } else {
            Plan::create([
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'features' => $this->features,
                'image' => $imagePath,
                'type' => $this->type,
            ]);
            $this->planModal = false;

            session()->flash('success', 'Plan created successfully!');
        }

        $this->resetFields();
        $this->loadPlans();
    }

    public function edit($id)
    {
        $this->planModal = true;

        $plan = Plan::find($id);
        $this->planId = $plan->id;
        $this->name = $plan->name;
        $this->type = $plan->type;
        $this->price = $plan->price;
        $this->description = $plan->description;
        $this->features = $plan->features;
        $this->editMode = true;

    }

    public function delete($id)
    {
        $plan = Plan::findOrFail($id);

        if ($plan->image) {
            Storage::disk('public')->delete($plan->image);
        }

        $plan->delete();

        session()->flash('success', 'Plan deleted successfully!');
        $this->loadPlans();
    }
    public function render()
    {
        return view('livewire.admin.manage-plan');
    }
}
