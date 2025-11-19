<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Membership;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    #[Validate('required|string')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $identifier = trim($this->email);

        $authenticated = false;

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $authenticated = Auth::attempt(['email' => $identifier, 'password' => $this->password], $this->remember);
        } else {
            $membership = Membership::where('token', $identifier)
                ->orWhere('mobile', $identifier)
                ->orWhere('email', $identifier)
                ->first();
            if ($membership && $membership->user && Hash::check($this->password, $membership->user->password)) {
                Auth::login($membership->user, $this->remember);
                $authenticated = true;
            }
        }

        if (! $authenticated) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $member = Auth::user()->membership ?? null;
        if (! $member || ($member->used_pin_count ?? 0) <= 0) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'E-PIN not assigned. Please redeem an E-PIN to access your account.',
            ]);
        }

        if (Auth::user()->is_admin) {
            redirect()->intended(route('admin.dashboard'));
            return;
        }
        redirect()->intended(route('member.dashboard'));
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 via-emerald-700 to-cyan-800 py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full">
        <div class="grid grid-cols-1 bg-white rounded-xl overflow-hidden shadow-xl">
          
            <div class="p-8 md:p-10">
                <h2 class="text-2xl font-extrabold text-gray-900">Welcome back</h2>
                <p class="mt-1 text-sm text-gray-600">Sign in to continue</p>
                <form wire:submit="login" class="mt-6 space-y-6">
                <!-- Identifier -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">MemberShip ID</label>
                    <div class="mt-1">
                        <input wire:model="email" id="email" type="text" required 
                            class="appearance-none block w-full px-3 py-2 border-2 border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>
                    @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative">
                        <input wire:model="password" id="password" type="password" required
                            class="appearance-none block w-full px-3 py-2 border-2 border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>
                    @error('password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input wire:model="remember" id="remember" type="checkbox"
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-teal-600 hover:text-teal-500">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        Sign in
                    </button>
                </div>
                </form>
                @if (Route::has('register'))
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">New to BiharShop?</span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('register') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Create an account</a>
                        </div>
                    </div>
                @endif
                <div class="mt-6 text-center">
                    <a href="{{ route('admin.login') }}" class="text-sm text-teal-600 hover:text-teal-500">Login as Administrator</a>
                </div>
            </div>
        </div>
    </div>
</div>
