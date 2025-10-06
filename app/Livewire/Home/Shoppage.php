<?php

namespace App\Livewire\Home;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('components.layouts.app')]

class Shoppage extends Component
{
    public function render()
    {
        return view('livewire.home.shoppage');
    }
}
