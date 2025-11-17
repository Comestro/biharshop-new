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
use App\Models\EPin;
use App\Models\BinaryTree;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $mobile = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $epin = '';
    public string $position = '';


    /**
     * Handle an incoming registration request.
     */
    public function mount(): void
    {
        $q = request()->query('epin');
        if ($q) $this->epin = (string) $q;
    
        if ($q) $this->epin = (string) $q;
        $pos = strtolower((string) (request()->query('position') ?? ''));
        if (in_array($pos, ['left','right'])) $this->position = $pos;
    }

    public function register(): void
    {
        $referer = null;
        $membershipId = null;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'epin' => ['required', 'regex:/^\d{6}$/'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $pin = EPin::where('code', $this->epin)->first();
        // dd($pin);
        if (!$pin) {
            $this->addError('epin', 'Invalid or already used E-PIN');
            return;
        }
        if (!$pin->used_by_membership_id) {
            $this->addError('epin', 'E-PIN has no sponsor assigned');
            return;
        }
        $sponsor = Membership::where('token', $pin->code)->first();
        // dd($sponsor);
        if (!$sponsor) {
            $this->addError('epin', 'Sponsor not verified or not found');
            return;
        }
        // dd($sponsor);
        Membership::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $this->mobile,
            'referal_id' => $pin->used_by_membership_id,
        ]);
        $newMember = Membership::where('user_id', $user->id)->first();
        if ($newMember) {
            $newMember->used_pin_count = ($newMember->used_pin_count ?? 0) + 1;
            $newMember->save();
            if (!ReferralTree::where('member_id', $newMember->id)->exists()) {
                ReferralTree::create(['member_id' => $newMember->id, 'parent_id' => $sponsor->id]);
            }
            
            $pin->update([
                'used_by_membership_id' => $newMember->id,
                'status' => 'used',
                'used_at' => now(),
            ]);
            $this->placeInBinaryTree($sponsor->id, $newMember->id);
        }

        // Redirect to dashboard after registration
        redirect()->route('dashboard');
    }
    private function placeInBinaryTree($parentId, $newMemberId)
    {
        $queue = [$parentId];
        while (!empty($queue)) {
            $current = array_shift($queue);
            $children = BinaryTree::where('parent_id', $current)->get();
            $left = $children->firstWhere('position', 'left');
            $right = $children->firstWhere('position', 'right');
            if ($this->position === 'left' && !$left) {
                BinaryTree::create(['member_id' => $newMemberId, 'parent_id' => $current, 'position' => 'left']);
                return;
            }
            if ($this->position === 'right' && !$right) {
                BinaryTree::create(['member_id' => $newMemberId, 'parent_id' => $current, 'position' => 'right']);
                return;
            }
            if (!$left) {
                BinaryTree::create(['member_id' => $newMemberId, 'parent_id' => $current, 'position' => 'left']);
                return;
            }
            if (!$right) {
                BinaryTree::create(['member_id' => $newMemberId, 'parent_id' => $current, 'position' => 'right']);
                return;
            }
            $queue[] = $left->member_id;
            $queue[] = $right->member_id;
        }
    }
}; ?>

<div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 via-emerald-700 to-cyan-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 bg-white rounded-xl overflow-hidden shadow-xl">
            <div class="p-8 md:p-10 bg-teal-50 border-r border-teal-100">
                <h2 class="text-2xl md:text-3xl font-extrabold text-teal-900">Grow with BiharShop Network</h2>
                <p class="mt-2 text-sm text-teal-800">Register to start building your team and earning.</p>
                <div class="mt-6 space-y-3 text-sm text-teal-900">
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Verified
                        referrals and position availability</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Binary
                        and referral trees</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-600"></span>
                        Transparent withdrawal charges</div>
                </div>
                <div class="mt-8">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-white text-teal-700 border border-teal-200 rounded-md font-medium hover:bg-gray-50 transition">Already
                        a member? Sign in</a>
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
                        @error('name')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1">
                            <input wire:model="email" id="email" type="email" required
                                class="appearance-none block w-full px-3 py-2 border-2 border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                                placeholder="you@example.com">
                        </div>
                        @error('email')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Mobile / Contact -->
                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile</label>
                        <div class="mt-1">
                            <input wire:model="mobile" id="mobile" type="number" required
                                class="appearance-none block w-full px-3 py-2 border-2 border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                                placeholder="98########">
                        </div>
                        @error('mobile')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Parent E-PIN -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Parent E-PIN</label>
                        <input type="text" wire:model="epin" @if(request()->query("epin")) disabled @endif maxlength="6" placeholder="Enter 6-digit E-PIN"
                            class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        @error('epin')
                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Use your sponsor/parent's E-PIN to join</p>
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
                            @error('password')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                                password</label>
                            <div class="mt-1">
                                <input wire:model="password_confirmation" id="password_confirmation" type="password"
                                    required
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
