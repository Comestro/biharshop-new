<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Membership;
use App\Models\BinaryTree;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MembershipSeeder extends Seeder
{
    public function run()
    {
        // Create root member
        $rootUser = User::create([
            'name' => 'Root Member',
            'email' => 'root@example.com',
            'password' => Hash::make('password123')
        ]);

        $rootMember = Membership::create([
            'user_id' => $rootUser->id,
            'token' => 'MEM001',
            'name' => 'Root Member',
            'email' => 'root@example.com',
            'mobile' => '9876543210',
            'isPaid' => true,
            'isVerified' => true,
            'status' => true
        ]);

        // Create left and right members
        $leftUser = User::create([
            'name' => 'Left Member',
            'email' => 'left@example.com',
            'password' => Hash::make('password123')
        ]);

        $leftMember = Membership::create([
            'user_id' => $leftUser->id,
            'token' => 'MEM002',
            'name' => 'Left Member',
            'email' => 'left@example.com',
            'mobile' => '9876543211',
            'referal_id' => $rootMember->id,
            'isPaid' => true,
            'isVerified' => true,
            'status' => true
        ]);

        $rightUser = User::create([
            'name' => 'Right Member',
            'email' => 'right@example.com',
            'password' => Hash::make('password123')
        ]);

        $rightMember = Membership::create([
            'user_id' => $rightUser->id,
            'token' => 'MEM003',
            'name' => 'Right Member',
            'email' => 'right@example.com',
            'mobile' => '9876543212',
            'referal_id' => $rootMember->id,
            'isPaid' => true,
            'isVerified' => true,
            'status' => true
        ]);

        // Create binary tree positions
        BinaryTree::create([
            'member_id' => $leftMember->id,
            'parent_id' => $rootMember->id,
            'position' => 'left'
        ]);

        BinaryTree::create([
            'member_id' => $rightMember->id,
            'parent_id' => $rootMember->id,
            'position' => 'right'
        ]);
    }
}
