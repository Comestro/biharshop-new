<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    protected function messages()
    {
        return [
            'email.required' => 'Email address is required',
            'password.required' => 'Password is required',
        ];
    }

    public function login()
    {
        $this->validate();

        if (Auth::guard('admin')->attempt([
            'email' => $this->email,
            'password' => $this->password
        ], $this->remember)) {
            session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        $this->addError('email', 'Invalid admin credentials');
        $this->reset('password');
    }

    public function render()
    {
        return view('livewire.admin.auth.login')
                ->layout('components.layouts.auth');
    }
}
