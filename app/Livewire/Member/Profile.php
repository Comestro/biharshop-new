<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Membership;

class Profile extends Component
{
    public $membership;
    
    public function mount()
    {
        $this->membership = auth()->user()->membership;
    }

    public function render()
    {
        return view('livewire.member.profile')
               ->layout('components.layouts.member');
    }
}
