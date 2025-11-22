<?php

namespace Database\Seeders;

use App\Models\EPin;
use App\Models\MembershipPlan;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Support\Facades\Hash;

class MembershipSeeder extends Seeder
{
    public function run()
    {
        // Ensure user exists
        $user = User::firstOrCreate([
            'id' => 1
        ], [
            'name' => 'top User',
            'email' => 'top@user.com',
            'password' => Hash::make('password')
        ]);
        $plan = Plan::create([
            'name' => 'welcome',
            'type' => 'main',
            'price' => 3000,
            'description' => 'xcfvgbhjnmk',

        ]);
        $epin = EPin::create([
            'code' => 1234567,
            'plan_amount' => 3000,
            'plan_name' => 'starter',
            'generated_by_admin_id' => 1,
            'plan_id' => $plan->id,
        ]);
        // Create membership
        $member = Membership::create([
            'membership_id' => 1234567,
            'token' => 'MEM001',
            'user_id' => $user->id,
            'name' => 'Top User',
            'email' => 'top@user.com',
            'mobile' => '9876543210',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'father_name' => 'Father',
            'mother_name' => 'Mother',
            'home_address' => 'Patna',
            'city' => 'Patna',
            'state' => 'Bihar',
            'nationality' => 'Indian',
            'bank_name' => 'SBI',
            'account_no' => '123456789012',
            'ifsc' => 'SBIN0001234',
            'pancard' => 'ABCDE1234F',
            'aadhar_card' => '123412341234',
            'isPaid' => true,
            'isVerified' => true,
            'status' => true,
            'used_pin_count' => 1
        ]);
        MembershipPlan::create([
            'membership_id' => 1,
            'plan_id' => 1,
            'epin_id' => $epin->id,
            'status' => 'active',

        ]);
        $epin->used_by_membership_id = $member->id;
        $epin->save();

        dd("Seeder executed successfully");
    }
}
