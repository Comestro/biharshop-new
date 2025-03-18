<?php

namespace App\Livewire\Membership;

use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree;

class BinaryPosition extends Component
{
    public $membership;
    public $referral_code;
    public $position;
    public $sponsor;
    public $availablePositions = [];

    public function mount(Membership $membership)
    {
        $this->membership = $membership;
        
        // Get sponsor from referral if exists
        if ($membership->referal_id) {
            $this->sponsor = Membership::find($membership->referal_id);
        } else {
            // Get first available sponsor
            $this->sponsor = Membership::where('isPaid', true)
                                     ->where('isVerified', true)
                                     ->whereDoesntHave('binaryPosition', function($q) {
                                         $q->whereNotNull('parent_id');
                                     })
                                     ->first();
        }
        
        $this->checkAvailablePositions();
    }

    public function updatedReferralCode()
    {
        if ($this->referral_code) {
            $sponsor = Membership::where('token', $this->referral_code)
                               ->where('isPaid', true)
                               ->where('isVerified', true)
                               ->first();
            
            if ($sponsor) {
                $this->sponsor = $sponsor;
                $this->checkAvailablePositions();
            }
        }
    }

    public function checkAvailablePositions()
    {
        $this->availablePositions = [];
        
        if (!$this->sponsor) return;

        $existingPositions = BinaryTree::where('parent_id', $this->sponsor->id)
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
            'member_id' => $this->membership->id,
            'parent_id' => $this->sponsor->id,
            'position' => $this->position
        ]);

        session()->flash('message', 'Position selected successfully!');
        return redirect()->route('member.dashboard');
    }

    public function render()
    {
        return view('livewire.membership.binary-position');
    }
}
