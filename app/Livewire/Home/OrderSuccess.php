<?php

namespace App\Livewire\Home;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('components.layouts.app')]

class OrderSuccess extends Component
{
    public function render()
    {
        return view('livewire.home.order-success');
    }
}
