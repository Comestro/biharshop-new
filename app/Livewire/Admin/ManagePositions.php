<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree;
use Livewire\WithPagination;

class ManagePositions extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedMember = null;
    public $sponsorId = '';
    public $position = '';
    public $viewingMember = null;

    public function selectMember($id)
    {
        $this->selectedMember = Membership::find($id);
    }

    public function viewTree($id)
    {
        $this->viewingMember = Membership::find($id);
    }

    public function updatePosition()
    {
        $this->validate([
            'sponsorId' => 'required|exists:memberships,id',
            'position' => 'required|in:left,right'
        ]);

        // Check if position is available
        $existingPosition = BinaryTree::where('parent_id', $this->sponsorId)
            ->where('position', $this->position)
            ->exists();

        if ($existingPosition) {
            session()->flash('error', 'Position already taken');
            return;
        }

        // Remove existing position if any
        BinaryTree::where('member_id', $this->selectedMember->id)->delete();

        // Create new position
        BinaryTree::create([
            'member_id' => $this->selectedMember->id,
            'parent_id' => $this->sponsorId,
            'position' => $this->position
        ]);

        session()->flash('message', 'Member position updated successfully');
        $this->reset(['selectedMember', 'sponsorId', 'position']);
    }

    public function render()
    {
        $members = Membership::where('isVerified', true)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('token', 'like', '%'.$this->search.'%');
                });
            })
            ->paginate(10);

        return view('livewire.admin.manage-positions', [
            'members' => $members
        ])->layout('components.layouts.admin');
    }
}
