<?php

use App\Models\User;
use App\Models\Membership;
use App\Models\ReferralTree;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $mobile = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $referral_code = '';
    public string $referrer_name = '';


    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        if ($this->referral_code) {
            $referrer = Membership::where('token', $this->referral_code)
                ->where('isVerified', true)
                ->first();

            $referrerByRefCode = Membership::where('referral_code', $this->referral_code)
                ->where('isVerified', true)
                ->first();

            if ($referrer) {
                // Check available positions
                $existingPositions = \App\Models\BinaryTree::where('parent_id', $referrer->id)
                    ->pluck('position')
                    ->toArray();

                if (count($existingPositions) >= 2) {
                    $this->referrer_name = $referrer->name . ' (No positions available)';
                    $this->addError('referral_code', 'This member has no available positions');
                } else {
                    $this->referrer_name = $referrer->name . ' (' . (2 - count($existingPositions)) . ' position(s) available)';
                }


            } elseif ($referrerByRefCode) {
                $existingReferrer = ReferralTree::where('parent_id', $referrerByRefCode->id);
                if ($existingReferrer) {
                    $this->referrer_name = $referrerByRefCode->name;
                } else {
                    $this->referrer_name = $referrerByRefCode->name . ' (Not available)';
                    $this->addError('referral_code', 'This member has no available ');
                }

            } else {
                $this->referrer_name = '';
            }
        } else {
            $this->referrer_name = '';
        }


        $referer = null;
        $membershipId = null;

        if ($this->referral_code) {

            // First check token
            $referer = Membership::where('token', $this->referral_code)
                ->where('isVerified', true)
                ->first();
            if (!$referer) {
                $referer = null;
            }

            // If not token → check referral_code

            $membershipId = Membership::where('referral_code', $this->referral_code)
                ->where('isVerified', true)
                ->first();

            if (!$membershipId) {
                $membershipId = null;
            }
        }
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'referral_code' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $exists = Membership::where('isVerified', true)
                        ->where(function ($q) use ($value) {
                            $q->where('token', $value)
                                ->orWhere('referral_code', $value);
                        })
                        ->exists();

                    if (!$exists) {
                        $fail('The referral code is invalid or user is not verified.');
                    }
                },
            ],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $existing = Membership::where(function ($q) use ($validated) {
            $q->where('email', $validated['email'])
                ->orWhere('mobile', $this->mobile);
        })->first();

        if ($existing) {
            if (!$existing->user_id) {
                $existing->user_id = $user->id;
                $existing->save();
            }
        } else {
            $token = $this->generateSequentialToken();
            Membership::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $this->mobile,
                'token' => $token,
                'referal_id' => $referer ? $referer->id : null,
                'membership_id' => $membershipId ? $membershipId->id : null,

            ]);
        }

        // Redirect to dashboard after registration
        redirect()->route('dashboard');
    }

    private function generateSequentialToken(): string
    {
        $lastMembership = Membership::orderBy('id', 'desc')->first();
        if (!$lastMembership || !$lastMembership->token) {
            return 'BSE1971';
        }
        $lastNumber = (int) str_replace('BSE', '', $lastMembership->token);
        return 'BSE' . ($lastNumber + 1);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 via-emerald-700 to-cyan-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 bg-white rounded-xl overflow-hidden shadow-xl">
            <div class="p-8 md:p-10 bg-teal-50 border-r border-teal-100">
                <h2 class="text-2xl md:text-3xl font-extrabold text-teal-900">Grow with BiharShop Network</h2>
                <p class="mt-2 text-sm text-teal-800">Register to start building your team and earning.</p>
                <div class="mt-6 space-y-3 text-sm text-teal-900">
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Verified referrals and position availability</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Binary and referral trees</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Transparent withdrawal charges</div>
                </div>
                <div class="mt-8">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-teal-700 border border-teal-200 rounded-md font-medium hover:bg-gray-50 transition">Already a member? Sign in</a>
                </div>
            </div>
            <div class="p-8 md:p-10">
                <h2 class="text-2xl font-extrabold text-gray-900">Create your account</h2>
                <p class="mt-1 text-sm text-gray-600">Join BiharShop and start your journey</p>
                <form wire:submit="register" class="mt-6 space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <div class="mt-1">
                        <input wire:model="name" id="name" type="text" required autofocus
                            class="appearance-none block w-full px-3 py-2 border-2 border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                            placeholder="John Doe">
                    </div>
                    @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <div class="mt-1">
                        <input wire:model="email" id="email" type="email" required
                            class="appearance-none block w-full px-3 py-2 border-2 border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                            placeholder="you@example.com">
                    </div>
                    @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Mobile / Contact -->
                <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile</label>
                    <div class="mt-1">
                        <input wire:model="mobile" id="mobile" type="text" required
                            class="appearance-none block w-full px-3 py-2 border-2 border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                            placeholder="98########">
                    </div>
                    @error('mobile') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Referral Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Referral Code (Optional)</label>
                    <input type="text" wire:model.live="referral_code" placeholder="Enter member ID of referrer"
                        class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    @if($referrer_name)
                        <p class="mt-1 text-sm text-teal-600">Referrer: {{ $referrer_name }}</p>
                    @endif
                    @error('referral_code') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1">
                        <input wire:model="password" id="password" type="password" required
                            class="appearance-none block w-full px-3 py-2 border-2 border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                    @error('password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                        password</label>
                    <div class="mt-1">
                        <input wire:model="password_confirmation" id="password_confirmation" type="password" required
                            class="appearance-none block w-full px-3 py-2 border-2 border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>             

                <!-- Terms & Privacy Policy -->
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                        class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the <a href="#" class="text-teal-600 hover:text-teal-500">Terms</a>
                        and <a href="#" class="text-teal-600 hover:text-teal-500">Privacy Policy</a>
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Already have an account?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}"
                        class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        Sign in instead
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
