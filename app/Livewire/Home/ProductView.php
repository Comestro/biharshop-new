<?php

namespace App\Livewire\Home;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('components.layouts.app')]

class ProductView extends Component
{
    public function render()
    {
        return view('livewire.home.product-view');
    }
}
