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
    public string $position = 'left';
    public string $sponsor_id = '';
    public $sponsor_name = null;


    /**
     * Handle an incoming registration request.
     */
    public function mount(): void
    {
        $q = request()->query('epin');
        if ($q)
            $this->epin = (string) $q;

        if ($q)
            $this->epin = (string) $q;
        $pos = strtolower((string) (request()->query('position') ?? ''));
        if (in_array($pos, ['left', 'right']))
            $this->position = $pos;

        $tok = request()->query('sponsor_id');
        if ($tok)
            $this->sponsor_id = (string) $tok;


    }

    public function register(): void
    {
        $referer = null;
        $membershipId = null;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'epin' => ['nullable', 'regex:/^\d{6}$/'],
            'sponsor_id' => ['nullable', 'string'],
            'position' => ['required'],
        ]);

        if (!$this->epin && !$this->sponsor_id) {
            $this->addError('epin', 'Provide E-PIN or Sponsor ID');
            $this->addError('sponsor_id', 'Provide E-PIN or Sponsor ID');
            return;
        }

        $validated['password'] = Hash::make('11111111');


        $sponsor = null;
        $pin = null;

        $pin = EPin::where('code', $this->epin)->where('status', 'available')->first();

        if (!$pin) {
            $this->addError('epin', 'Invalid or used E-PIN');
            return;
        }
        $sponsor = Membership::where('membership_id', $this->sponsor_id)->first();
        if (!$sponsor) {
            if ($pin) {
                $sponsor = Membership::where('id', $pin->issued_to_membership_id)->first();
            }
        }
        if (!$sponsor) {
            $this->addError('sponsor_id', 'Invalid E-PIN or Sponsor ID');
            return;
        }

        event(new Registered(($user = User::create($validated))));


        // dd($sponsor);
        Membership::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $this->mobile,
            'referal_id' => $sponsor->id,
            'membership_id' => $this->generateUniqueMembershipId(),
            'token' => $this->epin ?: null,
            'isPaid' => true,
            'status' => true,
        ]);
        $newMember = Membership::where('user_id', $user->id)->first();

        if ($newMember) {
            $newMember->used_pin_count = ($newMember->used_pin_count ?? 0) + 1;
            $newMember->save();
            if (!ReferralTree::where('member_id', $newMember->id)->exists()) {
                ReferralTree::create(['member_id' => $newMember->id, 'parent_id' => $sponsor->id]);
            }


            if ($pin) {
                $pin->update([
                    'used_by_membership_id' => $newMember->id,
                    'status' => 'used',
                    'used_at' => now(),
                ]);
            }
            $this->placeInBinaryTree($sponsor->id, $newMember->id);

            try {
                $existsToday = \App\Models\WalletTransaction::where('membership_id', $newMember->id)
                    ->where('type', 'daily_commission')
                    ->whereDate('created_at', now()->toDateString())
                    ->exists();
                $totalReceived = \App\Models\WalletTransaction::where('membership_id', $newMember->id)
                    ->where('type', 'daily_commission')
                    ->sum('amount');
                if (!$existsToday && $totalReceived < 480) {
                    \App\Models\WalletTransaction::create([
                        'membership_id' => $newMember->id,
                        'type' => 'daily_commission',
                        'amount' => 16,
                        'status' => 'confirmed',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
            }
        }

        // dd($pin);
        // update epin table
        if ($pin) {
            $pin->used_by_membership_id = $newMember->id;
            $pin->status = 'used';
            $pin->used_at = now();
            $pin->save();
        }

        Auth::login($user);
        redirect()->route('dashboard');
    }
    private function generateUniqueMembershipId()
    {
        do {
            $id = rand(1000000, 9999999); // 7-digit random
        } while (Membership::where('membership_id', $id)->exists());

        return $id;
    }

    private function placeInBinaryTree($parentId, $newMemberId)
    {
        $position = $this->position; // left or right

        $current = $parentId;

        while (true) {

            $existing = BinaryTree::where('parent_id', $current)
                ->where('position', $position)
                ->first();

            if (!$existing) {
                BinaryTree::create([
                    'member_id' => $newMemberId,
                    'parent_id' => $current,
                    'position' => $position,
                ]);
                return;
            }

            $current = $existing->member_id;
        }
    }

    public function refreshSponsorName()
    {
        $member = Membership::where('membership_id', $this->sponsor_id)->first();
        $this->sponsor_name = $member ? $member->name : null;
    }

}; ?>

<div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 via-emerald-700 to-cyan-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl w-full">
        <div class="grid grid-cols-1 bg-white rounded-xl overflow-hidden shadow-xl">
            <div class="p-8 md:p-10">
                <h2 class="text-2xl font-extrabold text-gray-900">Register </h2>
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
                        <label class="block text-sm font-medium text-gray-700"> E-PIN</label>
                        <input type="text" wire:model="epin" @if(request()->query("epin")) disabled @endif maxlength="6"
                            placeholder="Enter 6-digit E-PIN"
                            class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        @error('epin')
                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Use your sponsor/parent's E-PIN to join</p>
                    </div>

                    <!-- Or Sponsor Id -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sponsor Id</label>
                        <input type="text" wire:model="sponsor_id" wire:keyup="refreshSponsorName"
                            placeholder="Enter sponsor token"
                            class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-teal-500">

                        @error('sponsor_id')
                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 bg-teal-50 p-2">
                            @if ($sponsor_name)
                                {{ $sponsor_name }}
                            @elseif($sponsor_id == null)
                                {{ 'Alternatively, enter the sponsor token' }}
                            @else
                                {{ 'Searching... Not Found' }}
                            @endif
                        </p>
                    </div>
                    <!-- Or Upline Token -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Choose Position</label>
                        <select wire:model="position" placeholder="Enter sponsor token"
                            class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-teal-500">
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>

                        @error('position')
                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                        @enderror

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
</div>