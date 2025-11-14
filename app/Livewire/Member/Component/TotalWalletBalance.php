<?php

namespace App\Livewire\Member\Component;

use App\Models\BinaryTree;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TotalWalletBalance extends Component
{
   
    public $walletBalance = 0.00;
    public $memberId; 
    public $isWalletLocked = false;
    public $commissionHistory = [];

   

    public function render()
    {
        return view('livewire.member.component.total-wallet-balance');
    }
}
