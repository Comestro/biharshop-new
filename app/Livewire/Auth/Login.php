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

        $identifier = trim($this->email);

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $identifier, 'password' => $this->password], $this->remember)) {
                session()->regenerate();
                if (Auth::user()->is_admin) {
                    return redirect()->intended(route('admin.dashboard'));
                }
                return redirect()->intended(route('member.dashboard'));
            }
        } else {
            $membership = Membership::where('token', $identifier)
                ->orWhere('mobile', $identifier)
                ->orWhere('email', $identifier)
                ->first();

            if ($membership && $membership->user && Hash::check($this->password, $membership->user->password)) {
                Auth::login($membership->user, $this->remember);
                session()->regenerate();
                if (Auth::user()->is_admin) {
                    return redirect()->intended(route('admin.dashboard'));
                }
                return redirect()->intended(route('member.dashboard'));
            }
        }

        $this->addError('email', 'These credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login');
        }
}
