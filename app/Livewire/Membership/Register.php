<?php

namespace App\Livewire\Membership;

use App\Models\Membership;
use App\Models\BinaryTree;
use App\Models\ReferralTree;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;



#[Layout("components.layouts.member")]
class Register extends Component
{
    use WithFileUploads;

    // Personal Info
    public $currentStep = 0;

    public $name;

    public $email;

    public $mobile;

    public $whatsapp;

    public $date_of_birth;

    public $gender;

    public $nationality;

    public $marital_status;

    public $religion;

    public $father_name;

    public $mother_name;


    // Address Details
    public $home_address;

    public $city;


    public $state;

    // Nominee Details
    public $nominee_name;

    public $nominee_relation;

    // Bank Details
    public $bank_name;

    public $branch_name;

    public $account_no;

    public $ifsc;

    // Documents
    public $pancard;

    public $aadhar_card;

    public $image;

    public $existingImage;

    // Terms
    public $terms_and_condition = false;

    public $isSubmitted = false;

    public $membership = null;
    public $e_pin = '';
    public $tokenFromPin = null;
    public $token = null;

    protected $validationAttributes = [
        'terms_and_condition' => 'terms and conditions',
    ];

    public $states = [
        'Andhra Pradesh',
        'Arunachal Pradesh',
        'Assam',
        'Bihar',
        'Chhattisgarh',
        'Goa',
        'Gujarat',
        'Haryana',
        'Himachal Pradesh',
        'Jharkhand',
        'Karnataka',
        'Kerala',
        'Madhya Pradesh',
        'Maharashtra',
        'Manipur',
        'Meghalaya',
        'Mizoram',
        'Nagaland',
        'Odisha',
        'Punjab',
        'Rajasthan',
        'Sikkim',
        'Tamil Nadu',
        'Telangana',
        'Tripura',
        'Uttar Pradesh',
        'Uttarakhand',
        'West Bengal',
        'Andaman and Nicobar Islands',
        'Chandigarh',
        'Dadra and Nagar Haveli and Daman and Diu',
        'Delhi',
        'Jammu and Kashmir',
        'Ladakh',
        'Lakshadweep',
        'Puducherry',
    ];

    public $nominee_relations = [
        'Spouse',
        'Son',
        'Daughter',
        'Father',
        'Mother',
        'Brother',
        'Sister',
        'Grandson',
        'Granddaughter',
        'Uncle',
        'Aunt',
        'Nephew',
        'Niece',
        'Other',
    ];

    public function mount()
    {
        if (auth()->check()) {
            // Pre-fill from user data
            $user = auth()->user();
            $this->name = $user->name;
            $this->email = $user->email;

            $existingMembership = Membership::where('user_id', auth()->id())->first();
            if ($existingMembership) {
                $this->membership = $existingMembership;

                // Load existing data
                $this->name = $existingMembership->name;
                $this->email = $existingMembership->email;
                $this->mobile = $existingMembership->mobile;
                $this->whatsapp = $existingMembership->whatsapp;
                $this->date_of_birth = $existingMembership->date_of_birth;
                $this->gender = $existingMembership->gender;
                $this->nationality = $existingMembership->nationality;
                $this->marital_status = $existingMembership->marital_status;
                $this->religion = $existingMembership->religion;
                $this->father_name = $existingMembership->father_name;
                $this->mother_name = $existingMembership->mother_name;
                $this->home_address = $existingMembership->home_address;
                $this->city = $existingMembership->city;
                $this->state = $existingMembership->state;
                $this->nominee_name = $existingMembership->nominee_name;
                $this->nominee_relation = $existingMembership->nominee_relation;
                $this->bank_name = $existingMembership->bank_name;
                $this->branch_name = $existingMembership->branch_name;
                $this->account_no = $existingMembership->account_no;
                $this->ifsc = $existingMembership->ifsc;
                $this->pancard = $existingMembership->pancard;
                $this->aadhar_card = $existingMembership->aadhar_card;
                $this->existingImage = $existingMembership->image;

                // Gate by E-PIN assignment first
                $hasAssignedPin = \App\Models\EPin::where('used_by_membership_id', $existingMembership->id)->exists();
                $this->currentStep = $hasAssignedPin ? $this->determineCurrentStep($existingMembership) : 0;
                $isComplete = $existingMembership->isKycComplete();
                if ($isComplete) {
                    if ($existingMembership->isPaid) {
                        $this->isSubmitted = true;
                        return;
                    }
                    return redirect()->route('membership.payment', $existingMembership);
                }
                $this->isSubmitted = false;
            }

            // token
            if($existingMembership->token) {
                $this->token = $existingMembership->token;
            }
        }
    }

    protected function determineCurrentStep($membership)
    {
        if (!$membership->token) {
            return 0;            
        }
        if (! $membership->date_of_birth || ! $membership->gender) {
            return 1;
        }
        if (!$membership->father_name || !$membership->mother_name) {
            return 2;
        }
        if (!$membership->home_address || !$membership->city) {
            return 3;
        }
        if (!$membership->nominee_name) {
            return 4;
        }
        if (!$membership->bank_name) {
            return 5;
        }
        if (!$membership->pancard || !$membership->aadhar_card) {
            return 6;
        }
        if (!$membership->image || !$membership->terms_and_condition) {
            return 7;
        }

        return 7;
    }

    public function nextStep()
    {
        $result = $this->validateStep($this->currentStep);
        if ($result === false) {
            return;
        }
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function save()
    {
        $existingMembership = Membership::where('user_id', auth()->id())->first();
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'whatsapp' => $this->whatsapp,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'marital_status' => $this->marital_status,
            'religion' => $this->religion,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'home_address' => $this->home_address,
            'city' => $this->city,
            'state' => $this->state,
            'nominee_name' => $this->nominee_name,
            'nominee_relation' => $this->nominee_relation,
            'bank_name' => $this->bank_name,
            'branch_name' => $this->branch_name,
            'account_no' => $this->account_no,
            'ifsc' => $this->ifsc,
            'pancard' => $this->pancard,
            'aadhar_card' => $this->aadhar_card,
            'terms_and_condition' => $this->terms_and_condition,
        ];
        if ($this->image) {
            $data['image'] = $this->image->store('member-photos', 'public');
        }
        // Use the pin as token if available, otherwise fallback to existing or sequential
        $token = $this->tokenFromPin ?? ($existingMembership ? $existingMembership->token : $this->generateSequentialToken());
        Membership::updateOrCreate(
            ['user_id' => auth()->id()],
            array_merge($data, [
                'token' => $token,
                'user_id' => auth()->id(),
            ])
        );
    }

    protected function validateStep($step)
    {
        switch ($step) {
            case 0:
                $this->validate([
                    'e_pin' => 'required|regex:/^\d{6}$/|exists:epins,code',
                ]);
                $pin = \App\Models\EPin::where('code', $this->e_pin)
                    ->where('status','!=','used')
                    ->first();

                if (! $pin) {
                    $this->addError('e_pin', 'Invalid E-PIN or not assigned to your account');
                    return false;
                }
                // Store the valid pin code for later use as token
                $this->tokenFromPin = $pin->code;
                break;
            case 1:
                $this->validate([
                    'name' => 'required|string|min:3|max:100',
                    'date_of_birth' => 'required|date|before:today|after:1940-01-01',
                    'gender' => 'required|in:male,female,other',
                    

                ], [
                    'mobile.regex' => 'Please enter a valid 10 digit mobile number',
                    'whatsapp.regex' => 'Please enter a valid 10 digit WhatsApp number',
                    'date_of_birth.before' => 'Date of birth must be in the past',
                    'date_of_birth.after' => 'Please enter a valid date of birth',
                ]);
                break;

            case 2:
                $this->validate([
                    'father_name' => 'required|string|min:3|max:100',
                    'mother_name' => 'required|string|min:3|max:100',
                    'nationality' => 'required|string|max:50',
                    'marital_status' => 'required|in:single,married,divorced,widowed',
                    'religion' => 'required|string|max:50',
                ]);
                break;

            case 3:
                $this->validate([
                    'home_address' => 'required|string|min:10|max:255',
                    'city' => 'required|string|max:50',
                    'state' => 'required|string|max:50',
                ]);
                break;

            case 4:
                $this->validate([
                    'nominee_name' => 'required|string|min:3|max:100',
                    'nominee_relation' => 'required|string|max:50',
                ]);
                break;

            case 5:
                $this->validate([
                    'bank_name' => 'required|string|max:100',
                    'branch_name' => 'required|string|max:100',
                    'account_no' => 'required|min:9|max:18|regex:/^\d+$/',
                    'ifsc' => 'required|regex:/^[A-Za-z]{4}0[A-Z0-9]{6}$/',
                ], [
                    'account_no.regex' => 'Please enter a valid account number',
                    'ifsc.regex' => 'Please enter a valid IFSC code',
                ]);
                break;

            case 6:
                $this->validate([
                    'pancard' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|unique:memberships,pancard,' . $this->membership?->id,
                    'aadhar_card' => 'required|digits:12|unique:memberships,aadhar_card,' . $this->membership?->id,
                ], [
                    'pancard.regex' => 'Please enter a valid PAN card number',
                    'aadhar_card.digits' => 'Please enter a valid 12 digit Aadhar number',
                ]);
                break;

            case 7:
                $imageRule = $this->existingImage ? 'nullable' : 'required';
                $this->validate([
                    'image' => $imageRule . '|image|max:1024|dimensions:min_width=200,min_height=200',
                    'terms_and_condition' => 'accepted',
                ], [
                    'image.dimensions' => 'Image must be at least 200x200 pixels',
                    'image.max' => 'Image size must not exceed 1MB',
                ]);
                break;
        }
    }



    public function updatedIfsc($value)
    {
        $this->reset(['bank_name', 'branch_name']); // Clear existing values

        if (strlen($value) === 11) {
            try {
                $response = Http::timeout(5)->get("https://ifsc.razorpay.com/{$value}");

                if ($response->successful()) {
                    $data = $response->json();
                    $this->bank_name = $data['BANK'];
                    $this->branch_name = $data['BRANCH'];
                    $this->dispatch('bank-details-found');
                } else {
                    $this->addError('ifsc', 'Invalid IFSC Code');
                }
            } catch (\Exception $e) {
                $this->addError('ifsc', 'Unable to fetch bank details');
            }
        }
    }

    private function generateSequentialToken()
    {
        $lastMembership = Membership::orderBy('id', 'desc')->first();
        if (!$lastMembership) {
            return 'BSE1971';
        }
        $lastNumber = (int) str_replace('BSE', '', $lastMembership->token);

        return 'BSE' . ($lastNumber + 1);
    }
 


    public function register()
    {
        $result = $this->validateStep($this->currentStep);
        if ($result === false) {
            return;
        }

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'whatsapp' => $this->whatsapp,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'marital_status' => $this->marital_status,
            'religion' => $this->religion,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'home_address' => $this->home_address,
            'city' => $this->city,
            'state' => $this->state,
            'nominee_name' => $this->nominee_name,
            'nominee_relation' => $this->nominee_relation,
            'bank_name' => $this->bank_name,
            'branch_name' => $this->branch_name,
            'account_no' => $this->account_no,
            'ifsc' => $this->ifsc,
            'pancard' => $this->pancard,
            'aadhar_card' => $this->aadhar_card,
            'terms_and_condition' => $this->terms_and_condition,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('member-photos', 'public');
        }

        // Choose token: prefer validated pin, then input pin, else sequential
        $token = $this->tokenFromPin ?? $this->e_pin ?? $this->generateSequentialToken();

        // Persist membership with token and mark as paid right away for E-PIN
        $membership = Membership::updateOrCreate(
            ['user_id' => auth()->id()],
            array_merge($data, [
                'token' => $token,
                'user_id' => auth()->id(),
                'isPaid' => true,
                'status' => true,
            ])
        );

        // Update E-PIN usage
        if ($token) {
            $pin = \App\Models\EPin::where('code', $token)->first();
            if ($pin && (string)($pin->status ?? '') !== 'used') {
                $membership->used_pin_count = ($membership->used_pin_count ?? 0) + 1;
                $membership->save();
                $pin->update([
                    'used_by_membership_id' => $membership->id,
                    'status' => 'used',
                    'used_at' => now(),
                ]);
            }
        }

        // Create referral link if missing and sponsor known
        if (!ReferralTree::where('member_id', $membership->id)->exists() && $membership->referal_id) {
            ReferralTree::create(['member_id' => $membership->id, 'parent_id' => $membership->referal_id]);
        }

        // Place in binary tree once referral known
        if (!BinaryTree::where('member_id', $membership->id)->exists() && $membership->referal_id) {
            $this->placeInBinaryTree($membership->referal_id, $membership->id);
        }

        // Verify upon KYC completion
        if ($membership->isKycComplete() && !$membership->isVerified) {
            $membership->isVerified = true;
            $membership->save();
        }

        return redirect()->route('dashboard');
    }

    // private function placeInBinaryTree($parentId, $newMemberId)
    // {
    //     $queue = [$parentId];
    //     while (!empty($queue)) {
    //         $current = array_shift($queue);
    //         $children = BinaryTree::where('parent_id', $current)->get();
    //         $left = $children->firstWhere('position', 'left');
    //         $right = $children->firstWhere('position', 'right');
    //         if (!$left) {
    //             BinaryTree::create(['member_id' => $newMemberId, 'parent_id' => $current, 'position' => 'left']);
    //             return;
    //         }
    //         if (!$right) {
    //             BinaryTree::create(['member_id' => $newMemberId, 'parent_id' => $current, 'position' => 'right']);
    //             return;
    //         }
    //         $queue[] = $left->member_id;
    //         $queue[] = $right->member_id;
    //     }
    // }

    public function render()
    {
        if ($this->isSubmitted) {
            return view('livewire.membership.show');
        }

        return view('livewire.membership.register');
    }
}
