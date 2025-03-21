<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree;

class BinaryPosition extends Component
{
    public $member_id;
    public $position;
    public $parentMembership;
    public $availablePositions = [];

    public function mount($member_id)
    {

        
        $this->member_id = $member_id;
        $this->parentMembership = Membership::findOrFail($member_id);
        $this->checkAvailablePositions();
    }

    public function checkAvailablePositions()
    {
        $existingPositions = BinaryTree::where('parent_id', $this->member_id)
            ->pluck('position')
            ->toArray();

        if (!in_array('left', $existingPositions)) {
            $this->availablePositions[] = 'left';
        }
        if (!in_array('right', $existingPositions)) {
            $this->availablePositions[] = 'right';
        }
    }

    public function selectPosition()
    {
        $this->validate([
            'position' => 'required|in:left,right'
        ]);

        BinaryTree::create([
            'member_id' => auth()->user()->membership->id,
            'parent_id' => $this->member_id,
            'position' => $this->position
        ]);

        session()->flash('message', 'Position selected successfully!');
        return redirect()->route('member.tree');
    }

    public function render()
    {
        return view('livewire.member.binary-position')
            ->layout('components.layouts.member');
    }
}
