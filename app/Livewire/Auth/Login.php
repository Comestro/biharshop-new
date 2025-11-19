<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Membership;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required',
        'password' => 'required'
    ];

    public function login()
    {
        $this->validate();


        $membership = Membership::where('membership_id', $this->email)->first();
        if (!$membership) {
            $this->addError('email', 'These credentials do not match our records');
            return;
        }

        if ($membership && $membership->user && Hash::check($this->password, $membership->user->password)) {
            Auth::login($membership->user, $this->remember);
            session()->regenerate();
            $member = Auth::user()->membership ?? null;
            if (!$member || ($member->used_pin_count ?? 0) <= 0) {
                Auth::logout();
                $this->addError('email', 'Invalid Credencial.');
                return;
            }
            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('member.dashboard'));
        }


        $this->addError('email', 'These credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
