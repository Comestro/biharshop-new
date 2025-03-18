<?php

namespace App\Livewire\Membership;

use Livewire\Component;
use App\Models\Membership;

class Dashboard extends Component
{
    public $membership;

    public function mount()
    {
        $this->membership = Membership::where('user_id', auth()->id())->firstOrFail();
    }

    public function render()
    {
        return view('livewire.membership.dashboard');
    }
}
