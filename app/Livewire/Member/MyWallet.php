<?php

namespace App\Livewire\Member;

use App\Models\BinaryTree;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('components.layouts.member')]
class MyWallet extends Component
{
    public $walletBalance = 0.00;
    public $memberId;
    public $isWalletLocked = false;
    public $commissionHistory = [];

    public function mount()
    {
        $this->memberId = auth()->user()->membership->id;
        $this->calculateCommission($this->memberId);
    }

    private function calculateCommission($memberId)
    {
        $baseAmount = Auth::user()->membership->plan->price ?? 2999;
        $wallet = 0.0;

        $left = BinaryTree::where('parent_id', $memberId)->where('position', 'left')->first();
        $right = BinaryTree::where('parent_id', $memberId)->where('position', 'right')->first();

        if (!$left && !$right) {
            $this->commissionHistory[] = [
                'level' => '-',
                'status' => 'No Pair',
                'percentage' => '-',
                'commission' => 0,
                'left_member' => '❌ No left member',
                'right_member' => '❌ No right member',
                'detail' => 'No binary started yet. Add at least one member on both sides to activate binary income.',
            ];
            return;
        }

        if (!$left || !$right) {
            $missing = !$left ? 'left' : 'right';
            $available = $left ? 'Left' : 'Right';
            $availableMember = $left ? $left->member_id : $right->member_id;

            $this->commissionHistory[] = [
                'level' => '-',
                'status' => 'Partial',
                'percentage' => '-',
                'commission' => 0,
                'left_member' => $this->getToken($left->member_id ?? null),
                'right_member' => $this->getToken($right->member_id ?? null),
                'detail' => ucfirst($missing) . " side missing. " . ucfirst($available) . " side started with member: " . $this->getToken($availableMember),
            ];
            return;
        }

        [$leftDepth, $leftMembers] = $this->getDirectionalDepth($left->member_id, 'left');
        [$rightDepth, $rightMembers] = $this->getDirectionalDepth($right->member_id, 'right');

        // ✅ Convert member IDs to tokens
        $leftTokens = array_map(fn($id) => $this->getToken($id), $leftMembers);
        $rightTokens = array_map(fn($id) => $this->getToken($id), $rightMembers);

        // ✅ 16% commission
        if ($leftDepth >= 1 || $rightDepth >= 1) {
            $commission = ($baseAmount * 16) / 100;
            $wallet += $commission;

            $this->commissionHistory[] = [
                'level' => 1,
                'status' => 'Paid',
                'percentage' => '16%',
                'commission' => $commission,
                'left_member' => $leftTokens[0] ?? 'N/A',
                'right_member' => $rightTokens[0] ?? 'N/A',
                'detail' => 'Initial pair activated — Left: ' . ($leftTokens[0] ?? 'N/A') . ', Right: ' . ($rightTokens[0] ?? 'N/A'),
            ];
        }

        // ✅ 12% for matched levels
        $pairs = min($leftDepth, $rightDepth);
        if ($pairs > 1) {
            for ($i = 2; $i <= $pairs; $i++) {
                $commission = ($baseAmount * 12) / 100;
                $wallet += $commission;

                $this->commissionHistory[] = [
                    'level' => $i,
                    'status' => 'Paid',
                    'percentage' => '12%',
                    'commission' => $commission,
                    'left_member' => $leftTokens[$i - 1] ?? 'N/A',
                    'right_member' => $rightTokens[$i - 1] ?? 'N/A',
                    'detail' => 'Pair matched at level ' . $i . ' — Left: ' . ($leftTokens[$i - 1] ?? 'N/A') . ' | Right: ' . ($rightTokens[$i - 1] ?? 'N/A'),
                ];
            }
        }
        $bigpairs = max($leftDepth, $rightDepth);

        if ($bigpairs > 1) {
            $this->isWalletLocked = true;
        }

        // ✅ Pending pairs
        $leftExtra = $leftDepth - $pairs;
        $rightExtra = $rightDepth - $pairs;

        if ($leftExtra > 0) {
            $pendingMembers = array_slice($leftTokens, -$leftExtra);
            $this->commissionHistory[] = [
                'level' => '-',
                'status' => 'Pending',
                'percentage' => '-',
                'commission' => 0,
                'left_member' => implode(', ', $pendingMembers),
                'right_member' => '❌ None yet',
                'detail' => "Pending: {$leftExtra} unpaired left-side member(s). Next right-side member will complete a pair.",
            ];
        }

        if ($rightExtra > 0) {
            $pendingMembers = array_slice($rightTokens, -$rightExtra);
            $this->commissionHistory[] = [
                'level' => '-',
                'status' => 'Pending',
                'percentage' => '-',
                'commission' => 0,
                'left_member' => '❌ None yet',
                'right_member' => implode(', ', $pendingMembers),
                'detail' => "Pending: {$rightExtra} unpaired right-side member(s). Next left-side member will complete a pair.",
            ];
        }

        $this->walletBalance = $wallet;
    }

    /**
     * 🧭 Get directional depth (single branch)
     */
    private function getDirectionalDepth($memberId, $direction)
    {
        $depth = 1;
        $current = $memberId;
        $chain = [$current];

        while (true) {
            $next = BinaryTree::where('parent_id', $current)
                ->where('position', $direction)
                ->first();

            if (!$next)
                break;

            $depth++;
            $current = $next->member_id;
            $chain[] = $current;
        }

        return [$depth, $chain];
    }

    /**
     * 🔍 Fetch token from Memberships table
     */
    private function getToken($memberId)
    {
        if (!$memberId)
            return 'N/A';
        $member = Membership::find($memberId);
        return $member?->token ?? 'N/A';
    }
    public function render()
    {
        return view('livewire.member.my-wallet');
    }
}
