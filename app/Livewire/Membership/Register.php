<?php

namespace App\Livewire\Membership;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Membership;
use Illuminate\Support\Facades\Http;

class Register extends Component
{
    use WithFileUploads;

    // Personal Info
    public $currentStep = 1;
    public $name, $email, $mobile, $whatsapp, $date_of_birth, $gender;
    public $nationality, $marital_status, $religion;
    public $father_name, $mother_name;
    public $referral_code;
    public $referrer_name = '';

    // Address Details
    public $home_address, $city, $pincode, $state;

    // Nominee Details
    public $nominee_name, $nominee_relation;

    // Bank Details
    public $bank_name, $branch_name, $account_no, $ifsc;

    // Documents
    public $pancard, $aadhar_card;
    public $image;
    public $existingImage;

    // Terms
    public $terms_and_condition = false;

    public $isSubmitted = false;
    public $membership = null;

    protected $validationAttributes = [
        'terms_and_condition' => 'terms and conditions'
    ];

    public $states = [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand',
        'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
        'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
        'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
        'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
        'Andaman and Nicobar Islands', 'Chandigarh', 'Dadra and Nagar Haveli and Daman and Diu',
        'Delhi', 'Jammu and Kashmir', 'Ladakh', 'Lakshadweep', 'Puducherry'
    ];

    public $nominee_relations = [
        'Spouse', 'Son', 'Daughter', 'Father', 'Mother',
        'Brother', 'Sister', 'Grandson', 'Granddaughter',
        'Uncle', 'Aunt', 'Nephew', 'Niece', 'Other'
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

                // Redirect if membership exists but unpaid
                if (!$existingMembership->isPaid) {
                    return redirect()->route('membership.payment', $existingMembership);
                }

                if ($existingMembership->isPaid) {
                    $this->isSubmitted = true;
                    return;
                }
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
                $this->pincode = $existingMembership->pincode;
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

                // Set the current step based on the completion level
                $this->currentStep = $this->determineCurrentStep($existingMembership);
            }
        }
    }

    protected function determineCurrentStep($membership)
    {
        if (!$membership->name || !$membership->email || !$membership->mobile) return 1;
        if (!$membership->father_name || !$membership->mother_name) return 2;
        if (!$membership->home_address || !$membership->city) return 3;
        if (!$membership->nominee_name) return 4;
        if (!$membership->bank_name) return 5;
        if (!$membership->pancard || !$membership->aadhar_card) return 6;
        if (!$membership->image || !$membership->terms_and_condition) return 7;
        return 7;
    }

    public function nextStep()
    {
        $this->validateStep($this->currentStep);
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    protected function validateStep($step)
    {
        switch ($step) {
            case 1:
                $this->validate([
                    'name' => 'required|string|min:3|max:100',
                    'email' => 'required|email|unique:memberships,email,'.$this->membership?->id,
                    'mobile' => 'required|regex:/^[6-9]\d{9}$/|unique:memberships,mobile,'.$this->membership?->id,
                    'whatsapp' => 'nullable|regex:/^[6-9]\d{9}$/',
                    'date_of_birth' => 'required|date|before:today|after:1940-01-01',
                    'gender' => 'required|in:male,female,other',
                    'referral_code' => 'nullable|exists:memberships,token'
                ], [
                    'mobile.regex' => 'Please enter a valid 10 digit mobile number',
                    'whatsapp.regex' => 'Please enter a valid 10 digit WhatsApp number',
                    'date_of_birth.before' => 'Date of birth must be in the past',
                    'date_of_birth.after' => 'Please enter a valid date of birth'
                ]);
                break;

            case 2:
                $this->validate([
                    'father_name' => 'required|string|min:3|max:100',
                    'mother_name' => 'required|string|min:3|max:100',
                    'nationality' => 'required|string|max:50',
                    'marital_status' => 'required|in:single,married,divorced,widowed',
                    'religion' => 'required|string|max:50'
                ]);
                break;

            case 3:
                $this->validate([
                    'home_address' => 'required|string|min:10|max:255',
                    'city' => 'required|string|max:50',
                    'state' => 'required|string|max:50',
                    'pincode' => 'required|digits:6'
                ]);
                break;

            case 4:
                $this->validate([
                    'nominee_name' => 'required|string|min:3|max:100',
                    'nominee_relation' => 'required|string|max:50'
                ]);
                break;

            case 5:
                $this->validate([
                    'bank_name' => 'required|string|max:100',
                    'branch_name' => 'required|string|max:100',
                    'account_no' => 'required|min:9|max:18|regex:/^\d+$/',
                    'ifsc' => 'required|regex:/^[A-Za-z]{4}0[A-Z0-9]{6}$/'
                ], [
                    'account_no.regex' => 'Please enter a valid account number',
                    'ifsc.regex' => 'Please enter a valid IFSC code'
                ]);
                break;

            case 6:
                $this->validate([
                    'pancard' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|unique:memberships,pancard,'.$this->membership?->id,
                    'aadhar_card' => 'required|digits:12|unique:memberships,aadhar_card,'.$this->membership?->id
                ], [
                    'pancard.regex' => 'Please enter a valid PAN card number',
                    'aadhar_card.digits' => 'Please enter a valid 12 digit Aadhar number'
                ]);
                break;

            case 7:
                $imageRule = $this->existingImage ? 'nullable' : 'required';
                $this->validate([
                    'image' => $imageRule.'|image|max:1024|dimensions:min_width=200,min_height=200',
                    'terms_and_condition' => 'accepted'
                ], [
                    'image.dimensions' => 'Image must be at least 200x200 pixels',
                    'image.max' => 'Image size must not exceed 1MB'
                ]);
                break;
        }
    }

    public function updatedReferralCode()
    {
        if ($this->referral_code) {
            $referrer = Membership::where('token', $this->referral_code)
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
            } else {
                $this->referrer_name = '';
            }
        } else {
            $this->referrer_name = '';
        }
    }

    public function updatedPincode($value)
    {
        $this->reset(['city', 'state']); // Clear existing values

        if(strlen($value) === 6 && is_numeric($value)) {
            try {
                $response = Http::timeout(5)->get("https://api.postalpincode.in/pincode/{$value}");

                if($response->successful()) {
                    $data = $response->json();

                    if(isset($data[0]['Status']) && $data[0]['Status'] === 'Success') {
                        $postOffice = $data[0]['PostOffice'][0];
                        $this->city = $postOffice['District'];
                        $this->state = $postOffice['State'];
                        $this->dispatch('address-found');
                    } else {
                        $this->addError('pincode', 'Invalid PIN Code');
                    }
                }
            } catch (\Exception $e) {
                $this->addError('pincode', 'Unable to fetch address details');
            }
        }
    }

    public function updatedIfsc($value)
    {
        $this->reset(['bank_name', 'branch_name']); // Clear existing values

        if(strlen($value) === 11) {
            try {
                $response = Http::timeout(5)->get("https://ifsc.razorpay.com/{$value}");

                if($response->successful()) {
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

    public function register()
    {
        $this->validateStep($this->currentStep);

        $referer = null;
        if ($this->referral_code) {
            $referer = Membership::where('token', $this->referral_code)->first();
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
            'pincode' => $this->pincode,
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
            'referal_id' => $referer ? $referer->id : null,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('member-photos', 'public');
        }

        $membership = Membership::updateOrCreate(
            ['user_id' => auth()->id()],
            array_merge($data, [
                'token' => uniqid('MEM'),
                'user_id' => auth()->id()
            ])
        );

        return redirect()->route('membership.payment', $membership);
    }

    public function render()
    {
        if ($this->isSubmitted) {
            return view('livewire.membership.show');
        }
        return view('livewire.membership.register');
    }
}
