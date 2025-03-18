<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Membership;
use App\Models\BinaryTree;
use Faker\Factory as Faker;

class MembershipSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('en_IN'); // Using Indian locale for realistic data
        
        // Create root member
        $rootMember = Membership::create([
            'token' => 'MEM001',
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'mobile' => $faker->numerify('98########'),
            'date_of_birth' => $faker->date(),
            'gender' => 'male',
            'father_name' => $faker->name('male'),
            'mother_name' => $faker->name('female'),
            'home_address' => $faker->address,
            'city' => $faker->city,
            'state' => 'Bihar',
            'pincode' => $faker->numerify('8#####'),
            'nationality' => 'Indian',
            'bank_name' => 'State Bank of India',
            'account_no' => $faker->numerify('##################'),
            'ifsc' => 'SBIN' . $faker->numerify('#####'),
            'pancard' => $faker->regexify('[A-Z]{5}[0-9]{4}[A-Z]'),
            'aadhar_card' => $faker->numerify('############'),
            'isPaid' => true,
            'isVerified' => true,
            'status' => true
        ]);

        // Create 50 members
        $members = [];
        for ($i = 2; $i <= 51; $i++) {
            $member = Membership::create([
                'token' => 'MEM' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'mobile' => $faker->numerify('98########'),
                'date_of_birth' => $faker->date(),
                'gender' => $faker->randomElement(['male', 'female']),
                'father_name' => $faker->name('male'),
                'mother_name' => $faker->name('female'),
                'home_address' => $faker->address,
                'city' => $faker->city,
                'state' => 'Bihar',
                'pincode' => $faker->numerify('8#####'),
                'nationality' => 'Indian',
                'bank_name' => $faker->randomElement(['SBI', 'PNB', 'BOI', 'HDFC', 'ICICI']),
                'account_no' => $faker->numerify('##################'),
                'ifsc' => 'SBIN' . $faker->numerify('#####'),
                'pancard' => $faker->regexify('[A-Z]{5}[0-9]{4}[A-Z]'),
                'aadhar_card' => $faker->numerify('############'),
                'isPaid' => true,
                'isVerified' => true,
                'status' => true
            ]);
            $members[] = $member;
        }

        // Create binary tree structure
        $this->createBinaryTree($rootMember, $members);
    }

    private function createBinaryTree($parent, &$members)
    {
        if (empty($members)) return;

        // Add left child
        $leftChild = array_shift($members);
        if ($leftChild) {
            BinaryTree::create([
                'member_id' => $leftChild->id,
                'parent_id' => $parent->id,
                'position' => 'left'
            ]);
            $this->createBinaryTree($leftChild, $members);
        }

        // Add right child
        $rightChild = array_shift($members);
        if ($rightChild) {
            BinaryTree::create([
                'member_id' => $rightChild->id,
                'parent_id' => $parent->id,
                'position' => 'right'
            ]);
            $this->createBinaryTree($rightChild, $members);
        }
    }
}
