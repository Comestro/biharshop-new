<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Membership;
use App\Models\Product;
use App\Models\Category;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_members' => Membership::count(),
            'pending_verifications' => Membership::where('isPaid', true)
                                               ->where('isVerified', false)
                                               ->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count()
        ];

        return view('livewire.admin.dashboard', ['stats' => $stats]);
    }
}
