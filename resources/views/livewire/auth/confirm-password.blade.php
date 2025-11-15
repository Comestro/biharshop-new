<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 via-emerald-700 to-cyan-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 bg-white rounded-xl overflow-hidden shadow-xl">
            <div class="p-8 md:p-10 bg-teal-50 border-r border-teal-100">
                <h2 class="text-2xl md:text-3xl font-extrabold text-teal-900">Secure Area</h2>
                <p class="mt-2 text-sm text-teal-800">Please confirm your password to continue.</p>
                <div class="mt-6 space-y-3 text-sm text-teal-900">
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Protect sensitive actions</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Quick verification</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Continue securely</div>
                </div>
            </div>
            <div class="p-8 md:p-10">
                <h2 class="text-2xl font-extrabold text-gray-900">Confirm password</h2>
                <p class="mt-1 text-sm text-gray-600">This is a secure area of the application.</p>
                <x-auth-session-status class="mt-4 text-center" :status="session('status')" />
                <form wire:submit="confirmPassword" class="mt-6 flex flex-col gap-6">
                    <flux:input wire:model="password" :label="__('Password')" type="password" required autocomplete="new-password" :placeholder="__('Password')" />
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Confirm') }}</flux:button>
                </form>
            </div>
        </div>
    </div>
</div>
