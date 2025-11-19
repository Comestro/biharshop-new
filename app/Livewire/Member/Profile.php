<?php

namespace App\Livewire\Member;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Membership;
#[Layout('components.layouts.member')]

class Profile extends Component
{
    public $openPasswordModelName = false;
    public $membership;

    #[Validate('required|min:6')]
    public $password = '';
    #[Validate('required|same:password')]

    public $confirm_password = '';

    public function mount()
    {
        $this->membership = auth()->user()->membership;
    }
    public function normalPasswordChange()
    {
        $this->openPasswordModelName = 'normalPassword';
    }
    public function epinPasswordChange()
    {
        $this->openPasswordModelName = 'epinPassword';
    }
    public function closePasswordChangeModel()
    {
        $this->openPasswordModelName = false;
    }
    public function updatePasswordFunction()
    {
        $this->validate();
        if ($this->openPasswordModelName === 'normalPassword') {
            $user = User::find(Auth::id())->first();
            if ($user) {
                $user->password = Hash::make($this->password);
                $user->save();
            }

            $this->password = '';
            $this->openPasswordModelName = false;
        } elseif ($this->openPasswordModelName === 'epinPassword') {
            $membership = Membership::where('user_id', Auth::id())->first();

            if ($membership) {
                $membership->epin_password = encrypt($this->password); // encrypt password
                $membership->save();
            }
            $this->password = '';
            $this->openPasswordModelName = false;
        } else {
            return;
        }
        $this->mount();
    }


    public function render()
    {
        return view('livewire.member.profile');
    }
}
