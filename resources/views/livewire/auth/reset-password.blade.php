<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PasswordReset) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 via-emerald-700 to-cyan-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 bg-white rounded-xl overflow-hidden shadow-xl">
            <div class="p-8 md:p-10 bg-teal-50 border-r border-teal-100">
                <h2 class="text-2xl md:text-3xl font-extrabold text-teal-900">Secure Your Account</h2>
                <p class="mt-2 text-sm text-teal-800">Update your password safely and keep your profile protected.</p>
                <div class="mt-6 space-y-3 text-sm text-teal-900">
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Strong password requirements</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> One-click reset link</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Seamless login after reset</div>
                </div>
                <div class="mt-8">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-teal-700 border border-teal-200 rounded-md font-medium hover:bg-gray-50 transition">Back to Login</a>
                </div>
            </div>
            <div class="p-8 md:p-10">
                <h2 class="text-2xl font-extrabold text-gray-900">Reset password</h2>
                <p class="mt-1 text-sm text-gray-600">Please enter your new password below</p>
                <x-auth-session-status class="mt-4 text-center" :status="session('status')" />
                <form wire:submit="resetPassword" class="mt-6 flex flex-col gap-6">
                    <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />
                    <flux:input wire:model="password" :label="__('Password')" type="password" required autocomplete="new-password" :placeholder="__('Password')" />
                    <flux:input wire:model="password_confirmation" :label="__('Confirm password')" type="password" required autocomplete="new-password" :placeholder="__('Confirm password')" />
                    <div>
                        <flux:button type="submit" variant="primary" class="w-full">{{ __('Reset password') }}</flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
