<?php

namespace App\Livewire\Admin;

use App\Models\ReferralTree;
use App\Models\WalletTransaction;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Membership;
use App\Models\BinaryTree;
use Illuminate\Support\Facades\Http;

#[Layout('components.layouts.admin')]
class Members extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';
    // Removed selectedMembership and showModal

    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
        'search' => ['except' => '']
    ];

    // Removed showMemberDetails

    // Removed approveMember logic (moved to ViewMember.php)

    private function recordBinaryCommissions($memberId)
    {
        $baseAmount = $this->selectedMembership->plan?->price ?? 3000;

        $left = BinaryTree::where('parent_id', $memberId)->where('position', 'left')->first();
        $right = BinaryTree::where('parent_id', $memberId)->where('position', 'right')->first();

        if (!$left || !$right) {
            return;
        }

        [$leftDepth, $leftMembers] = $this->getDirectionalDepth($left->member_id, 'left');
        [$rightDepth, $rightMembers] = $this->getDirectionalDepth($right->member_id, 'right');

        $leftTokens = array_map(fn($id) => $this->getToken($id), $leftMembers);
        $rightTokens = array_map(fn($id) => $this->getToken($id), $rightMembers);

        $maxDepth = max($leftDepth, $rightDepth);
        $minDepth = min($leftDepth, $rightDepth);
        $todayCount = WalletTransaction::where('membership_id', $memberId)
            ->where('type', 'binary_commission')
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $capRemaining = max(25 - $todayCount, 0);

        if ($maxDepth >= 2 && $minDepth >= 1) {
            $commission = ($baseAmount * 16) / 100;
            $recorded = WalletTransaction::where('membership_id', $memberId)
                ->where('type', 'binary_commission')
                ->where('meta->level', 1)
                ->exists();
            if (!$recorded && $capRemaining > 0) {
                WalletTransaction::create([
                    'membership_id' => $memberId,
                    'type' => 'binary_commission',
                    'amount' => $commission,
                    'status' => 'confirmed',
                    'meta' => [
                        'level' => 1,
                        'left_member' => $leftTokens[0] ?? 'N/A',
                        'right_member' => $rightTokens[0] ?? 'N/A',
                        'percentage' => 16
                    ]
                ]);
                $capRemaining--;
            }
        }

        $pairs = $minDepth;
        if ($pairs > 1) {
            for ($i = 2; $i <= $pairs; $i++) {
                $commission = ($baseAmount * 12) / 100;
                $recorded = WalletTransaction::where('membership_id', $memberId)
                    ->where('type', 'binary_commission')
                    ->where('meta->level', $i)
                    ->exists();
                if (!$recorded && $capRemaining > 0) {
                    WalletTransaction::create([
                        'membership_id' => $memberId,
                        'type' => 'binary_commission',
                        'amount' => $commission,
                        'status' => 'confirmed',
                        'meta' => [
                            'level' => $i,
                            'left_member' => $leftTokens[$i - 1] ?? 'N/A',
                            'right_member' => $rightTokens[$i - 1] ?? 'N/A',
                            'percentage' => 12
                        ]
                    ]);
                    $capRemaining--;
                }
            }
        }
    }

    private function getDirectionalDepth($memberId, $direction)
    {
        $depth = 1;
        $current = $memberId;
        $chain = [$current];
        while (true) {
            $next = BinaryTree::where('parent_id', $current)
                ->where('position', $direction)
                ->first();
            if (!$next) break;
            $depth++;
            $current = $next->member_id;
            $chain[] = $current;
        }
        return [$depth, $chain];
    }

    private function getToken($memberId)
    {
        if (!$memberId) return 'N/A';
        return Membership::find($memberId)?->token ?? 'N/A';
    }

    private function distributeReferralCommissions(Membership $member)
    {
        $amount = $member->plan?->price ?? 3000;
        $levels = [3, 2, 1, 1, 1];

        $currentId = $member->id;
        for ($i = 0; $i < 5; $i++) {
            $parent = ReferralTree::where('member_id', $currentId)->first();
            if (!$parent || !$parent->parent_id) break;
            $parentId = $parent->parent_id;

            $percent = $levels[$i];
            $calc = ($amount * $percent) / 100;

            $exists = WalletTransaction::where('membership_id', $parentId)
                ->where('type', 'referral_commission')
                ->where('meta->child_id', $member->token)
                ->where('meta->level', $i + 1)
                ->exists();
            if (!$exists) {
                WalletTransaction::create([
                    'membership_id' => $parentId,
                    'type' => 'referral_commission',
                    'amount' => $calc,
                    'status' => 'confirmed',
                    'meta' => [
                        'level' => $i + 1,
                        'child_id' => $member->token,
                        'percentage' => $percent
                    ]
                ]);
            }

            $currentId = $parentId;
        }
    }

    private function sendMembershipMessage($mobile, $name, $membershipid)
    {
        $response = Http::withHeaders([
            'authkey' => env('MSG91_AUTH_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://control.msg91.com/api/v5/flow', [
                    'template_id' => '683e9d01d6fc053f9a6f39d3', // replace with your approved template ID
                    'short_url' => 0, // 1 to enable short links, 0 to disable
                    'recipients' => [
                        [
                            'mobiles' => '91' . $mobile,
                            'name' => $name,
                            'membershipid' => $membershipid,
                        ]
                    ]
                ]);

        if ($response->successful()) {
            return response()->json(['message' => 'SMS sent successfully']);
        } else {
            return response()->json(['error' => 'Failed to send SMS', 'details' => $response->body()], 500);
        }
    }

    public function render()
    {
        $query = Membership::query()
            ->when($this->statusFilter === 'pending', function ($q) {
                return $q->where('isPaid', true)->where('isVerified', false);
            })
            ->when($this->statusFilter === 'verified', function ($q) {
                return $q->where('isVerified', true);
            })
            ->when($this->statusFilter === 'unpaid', function ($q) {
                return $q->where('isPaid', false);
            })
            ->when($this->search, function ($q) {
                return $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('mobile', 'like', '%' . $this->search . '%')
                        ->orWhere('token', 'like', '%' . $this->search . '%');
                });
            });

        return view('livewire.admin.members', [
            'members' => $query->latest()->paginate(10)
        ]);
    }
}
