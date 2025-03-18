<?php

namespace App\Livewire\Membership;

use App\Models\BinaryPosition as Position;
use App\Models\Membership;
use Livewire\Component;

class BinaryPosition extends Component
{
    public $membership_id;
    public $parent_id;
    public $position;

    public function mount($membership_id)
    {
        $this->membership_id = $membership_id;
    }

    public function assignPosition()
    {
        $this->validate([
            'parent_id' => 'required|exists:memberships,id',
            'position' => 'required|in:left,right'
        ]);

        Position::create([
            'membership_id' => $this->membership_id,
            'parent_id' => $this->parent_id,
            'position' => $this->position
        ]);

        session()->flash('message', 'Position assigned successfully!');
    }

    public function render()
    {
        $members = Membership::where('isPaid', true)
                           ->where('isVerified', true)
                           ->get();
        
        return view('livewire.membership.binary-position', [
            'members' => $members
        ]);
    }
}
