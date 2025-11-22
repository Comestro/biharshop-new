<?php

namespace App\Livewire\Member;

use App\Models\Membership;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.member')]
class IdCard extends Component
{
    public function render()
    {
        $membership = auth()->user()->membership;
        return view('livewire.member.id-card', [
            'membership' => $membership,
        ]);
    }
}

