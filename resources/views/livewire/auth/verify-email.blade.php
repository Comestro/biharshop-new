<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 via-emerald-700 to-cyan-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 bg-white rounded-xl overflow-hidden shadow-xl">
            <div class="p-8 md:p-10 bg-teal-50 border-r border-teal-100">
                <h2 class="text-2xl md:text-3xl font-extrabold text-teal-900">Activate Your Account</h2>
                <p class="mt-2 text-sm text-teal-800">Verify your email to unlock all features and start building your network.</p>
                <div class="mt-6 space-y-3 text-sm text-teal-900">
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Fast verification email</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Enhanced security</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Access member tools</div>
                </div>
            </div>
            <div class="p-8 md:p-10">
                <h2 class="text-2xl font-extrabold text-gray-900">Verify email</h2>
                <p class="mt-1 text-sm text-gray-600">We sent a verification link to your email.</p>
                <div class="mt-4">
                    <flux:text class="text-center">
                        {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
                    </flux:text>
                </div>
                @if (session('status') == 'verification-link-sent')
                    <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600 mt-2">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </flux:text>
                @endif
                <div class="flex flex-col items-center justify-between space-y-3 mt-6">
                    <flux:button wire:click="sendVerification" variant="primary" class="w-full">
                        {{ __('Resend verification email') }}
                    </flux:button>
                    <flux:link class="text-sm cursor-pointer" wire:click="logout">
                        {{ __('Log out') }}
                    </flux:link>
                </div>
            </div>
        </div>
    </div>
</div>
