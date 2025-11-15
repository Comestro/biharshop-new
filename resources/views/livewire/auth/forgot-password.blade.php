<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 via-emerald-700 to-cyan-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 bg-white rounded-xl overflow-hidden shadow-xl">
            <div class="p-8 md:p-10 bg-teal-50 border-r border-teal-100">
                <h2 class="text-2xl md:text-3xl font-extrabold text-teal-900">Forgot Password</h2>
                <p class="mt-2 text-sm text-teal-800">Enter your email to receive a password reset link.</p>
                <div class="mt-6 space-y-3 text-sm text-teal-900">
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Secure recovery process</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Quick email delivery</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Continue where you left off</div>
                </div>
                <div class="mt-8">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-teal-700 border border-teal-200 rounded-md font-medium hover:bg-gray-50 transition">Back to Login</a>
                </div>
            </div>
            <div class="p-8 md:p-10">
                <h2 class="text-2xl font-extrabold text-gray-900">Reset link request</h2>
                <p class="mt-1 text-sm text-gray-600">We’ll email you a link to reset your password.</p>
                <x-auth-session-status class="mt-4 text-center" :status="session('status')" />
                <form wire:submit="sendPasswordResetLink" class="mt-6 flex flex-col gap-6">
                    <flux:input wire:model="email" :label="__('Email Address')" type="email" required autofocus placeholder="email@example.com" />
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Email password reset link') }}</flux:button>
                </form>
            </div>
        </div>
    </div>
</div>
