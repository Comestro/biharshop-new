<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Membership;
use App\Models\ReferralTree;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            // CategorySeeder::class,
            // ProductSeeder::class,
            // MembershipSeeder::class
        ]);

        // if (! User::where('email', 'test@example.com')->exists()) {
        //     User::factory()->create([
        //         'name' => 'Test User',
        //         'email' => 'test@example.com',
        //     ]);
        // }

        // $existing = Membership::whereIn('token', ['TEST001','TEST002','TEST003','TEST004','TEST005'])->count();
        // if ($existing === 0) {
        //     $m1 = Membership::create([
        //         'token' => 'TEST001',
        //         'name' => 'Referral Root 1',
        //         'email' => 'referral_test1@example.com',
        //         'mobile' => '9800000001',
        //         'isPaid' => true,
        //         'isVerified' => true,
        //         'status' => true,
        //     ]);
        //     $m2 = Membership::create([
        //         'token' => 'TEST002',
        //         'name' => 'Referral Child 2',
        //         'email' => 'referral_test2@example.com',
        //         'mobile' => '9800000002',
        //         'isPaid' => true,
        //         'isVerified' => true,
        //         'status' => true,
        //         'referal_id' => $m1->id,
        //     ]);
        //     $m3 = Membership::create([
        //         'token' => 'TEST003',
        //         'name' => 'Referral Child 3',
        //         'email' => 'referral_test3@example.com',
        //         'mobile' => '9800000003',
        //         'isPaid' => true,
        //         'isVerified' => true,
        //         'status' => true,
        //         'referal_id' => $m2->id,
        //     ]);
        //     $m4 = Membership::create([
        //         'token' => 'TEST004',
        //         'name' => 'Referral Child 4',
        //         'email' => 'referral_test4@example.com',
        //         'mobile' => '9800000004',
        //         'isPaid' => true,
        //         'isVerified' => true,
        //         'status' => true,
        //         'referal_id' => $m3->id,
        //     ]);
        //     $m5 = Membership::create([
        //         'token' => 'TEST005',
        //         'name' => 'Referral Child 5',
        //         'email' => 'referral_test5@example.com',
        //         'mobile' => '9800000005',
        //         'isPaid' => true,
        //         'isVerified' => true,
        //         'status' => true,
        //         'referal_id' => $m4->id,
        //     ]);

        //     ReferralTree::create(['member_id' => $m2->id, 'parent_id' => $m1->id]);
        //     ReferralTree::create(['member_id' => $m3->id, 'parent_id' => $m2->id]);
        //     ReferralTree::create(['member_id' => $m4->id, 'parent_id' => $m3->id]);
        //     ReferralTree::create(['member_id' => $m5->id, 'parent_id' => $m4->id]);

        //     $levels = [3, 2, 1, 1, 1];
        //     $base = 3000;
        //     $current = $m5->id;
        //     $childToken = $m5->token;
        //     $created = 0;
        //     for ($i = 0; $i < 5; $i++) {
        //         $parent = ReferralTree::where('member_id', $current)->first();
        //         if (! $parent || ! $parent->parent_id) { break; }
        //         $parentId = $parent->parent_id;
        //         $percent = $levels[$i];
        //         $amount = ($base * $percent) / 100;
        //         $exists = WalletTransaction::where('membership_id', $parentId)
        //             ->where('type', 'referral_commission')
        //             ->where('meta->child_id', $childToken)
        //             ->where('meta->level', $i + 1)
        //             ->exists();
        //         if (! $exists) {
        //             WalletTransaction::create([
        //                 'membership_id' => $parentId,
        //                 'type' => 'referral_commission',
        //                 'amount' => $amount,
        //                 'status' => 'confirmed',
        //                 'meta' => [
        //                     'level' => $i + 1,
        //                     'child_id' => $childToken,
        //                     'percentage' => $percent,
        //                 ],
        //             ]);
        //             $created++;
        //         }
        //         $current = $parentId;
        //     }

        //     $totalRoot = WalletTransaction::where('membership_id', $m1->id)->where('type','referral_commission')->sum('amount');
        //     $totalM2 = WalletTransaction::where('membership_id', $m2->id)->where('type','referral_commission')->sum('amount');
        //     $totalM3 = WalletTransaction::where('membership_id', $m3->id)->where('type','referral_commission')->sum('amount');
        //     $totalM4 = WalletTransaction::where('membership_id', $m4->id)->where('type','referral_commission')->sum('amount');

        //     echo "Referral commission records created: {$created}\n";
        //     echo "Totals -> TEST001: ₹" . number_format($totalRoot, 2) . ", TEST002: ₹" . number_format($totalM2, 2) . ", TEST003: ₹" . number_format($totalM3, 2) . ", TEST004: ₹" . number_format($totalM4, 2) . "\n";
        // } else {
        //     echo "Test referral members already exist, skipping creation.\n";
        // }
    }
}
