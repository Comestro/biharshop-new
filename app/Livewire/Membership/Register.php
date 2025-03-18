<?php

namespace App\Livewire\Membership;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Membership;

class Register extends Component
{
    use WithFileUploads;

    // Personal Info
    public $currentStep = 1;
    public $name, $email, $mobile, $whatsapp, $date_of_birth, $gender;
    public $nationality, $marital_status, $religion;
    public $father_name, $mother_name;
    
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

    protected $validationAttributes = [
        'terms_and_condition' => 'terms and conditions'
    ];

    public function mount()
    {
        if (auth()->check()) {
            $existingMembership = Membership::where('user_id', auth()->id())->first();
            if ($existingMembership) {
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
                    'name' => 'required',
                    'email' => 'required|email',
                    'mobile' => 'required',
                    'date_of_birth' => 'required|date',
                    'gender' => 'required'
                ]);
                break;
            case 2:
                $this->validate([
                    'father_name' => 'required',
                    'mother_name' => 'required',
                    'nationality' => 'required',
                    'marital_status' => 'required',
                    'religion' => 'required'
                ]);
                break;
            case 3:
                $this->validate([
                    'home_address' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'pincode' => 'required'
                ]);
                break;
            case 4:
                $this->validate([
                    'nominee_name' => 'required',
                    'nominee_relation' => 'required'
                ]);
                break;
            case 5:
                $this->validate([
                    'bank_name' => 'required',
                    'branch_name' => 'required',
                    'account_no' => 'required',
                    'ifsc' => 'required'
                ]);
                break;
            case 6:
                $this->validate([
                    'pancard' => 'required',
                    'aadhar_card' => 'required'
                ]);
                break;
            case 7:
                $this->validate([
                    'image' => 'required|image|max:1024',
                    'terms_and_condition' => 'accepted'
                ]);
                break;
        }
    }

    public function register()
    {
        $this->validateStep($this->currentStep);
        
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
            'terms_and_condition' => $this->terms_and_condition
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
        return view('livewire.membership.register');
    }
}
