<?php
namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
#[Layout('components.layouts.admin')]
class ViewMember extends Component
{
    public $isWalletLocked = false;
    public $lockedDaily = 0.00;
    public $availableBalance = 0.00;
    public $binaryCommissionTotal = 0.00;
    public $referralCommissionTotal = 0.00;
    public $dailyCommissionTotal = 0.00;
    public $binaryCommissionTx = [];
    public $referralCommissionTx = [];
    public $dailyCommissionTx = [];
    public function approveMember()
    {
        if ($this->member && !$this->member->isVerified) {
            $this->member->isVerified = true;
            $this->member->save();
            session()->flash('message', 'Member approved successfully.');
            // Optionally refresh member data
            $this->member = Membership::with([
                'referrer',
                'referrals',
                'binaryPosition.parent',
                'children.member'
            ])->findOrFail($this->member->id);
        }
    }
    public $member;
    public $activeTab = 'tree';
    public $leftTeamSize = 0;
    public $rightTeamSize = 0;
    public $totalTeamSize = 0;
    public $walletBalance = 0.00;
    public $transactions = [];
    public $withdrawals = [];
    public $binaryUplines = [];
    public $referralUplines = [];
   
    protected $validTabs = ['personal', 'financial', 'network', 'wallet', 'tree', 'binary_commission', 'referral_commission', 'daily_commission'];
    protected $listeners = ['treeNodeSelected' => 'navigateToMember'];

    public function mount($id)
    {
        $this->member = Membership::with([
            'referrer',
            'referrals',
            'binaryPosition.parent',
            'children.member'
        ])->findOrFail($id);
        
        // Calculate team sizes
        $this->calculateTeamSizes();
        $this->loadWalletData();
        $this->loadCommissionData();
        $this->loadUplines();
    }
    
    protected function calculateTeamSizes()
    {
        $this->leftTeamSize = 0;
        $this->rightTeamSize = 0;
        
        if ($this->member) {
            // Get left and right legs first
            $leftChild = BinaryTree::where('parent_id', $this->member->id)
                ->where('position', 'left')
                ->first();
                
            $rightChild = BinaryTree::where('parent_id', $this->member->id)
                ->where('position', 'right')
                ->first();
            
            // Count members in left leg
            if ($leftChild) {
                $this->leftTeamSize = 1 + $this->countTreeMembers($leftChild->member_id);
            }
            
            // Count members in right leg
            if ($rightChild) {
                $this->rightTeamSize = 1 + $this->countTreeMembers($rightChild->member_id);
            }
            
            // Calculate total team size
            $this->totalTeamSize = $this->leftTeamSize + $this->rightTeamSize;
        }
    }
    
    protected function countTreeMembers($memberId)
    {
        $count = 0;
        $children = BinaryTree::where('parent_id', $memberId)->get();
        
        foreach ($children as $child) {
            $count++; // Count this child
            $count += $this->countTreeMembers($child->member_id); // Add all descendants
        }
        
        return $count;
    }

    protected function loadWalletData()
    {
        $credits = WalletTransaction::where('membership_id', $this->member->id)
            ->whereIn('type', ['binary_commission', 'referral_commission', 'daily_commission'])
            ->where('status', 'confirmed')
            ->sum('amount');

        $debits = Withdrawal::where('membership_id', $this->member->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        $this->walletBalance = $credits - $debits;
        $this->isWalletLocked = !$this->hasFirstPair($this->member->id);
        $this->lockedDaily = $this->isWalletLocked
            ? WalletTransaction::where('membership_id', $this->member->id)
                ->where('type', 'daily_commission')
                ->where('status', 'confirmed')
                ->sum('amount')
            : 0.00;
        $this->availableBalance = max($this->walletBalance - $this->lockedDaily, 0);

        $this->transactions = WalletTransaction::where('membership_id', $this->member->id)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->toArray();

        $this->withdrawals = Withdrawal::where('membership_id', $this->member->id)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->toArray();
    }

    public function refreshWallet()
    {
        $this->member = Membership::with([
            'referrer',
            'referrals',
            'binaryPosition.parent',
            'children.member'
        ])->findOrFail($this->member->id);
        $this->calculateTeamSizes();
        $this->generateBinaryCommissions();
        $this->generateDailyCashback();
        $this->loadWalletData();
        $this->loadCommissionData();
        $this->loadUplines();
    }

    protected function generateBinaryCommissions()
    {
        $memberId = $this->member->id;
        $baseAmount = $this->member->plan?->price ?? 3000;

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

    protected function getDirectionalDepth($memberId, $direction)
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

    protected function getToken($memberId)
    {
        if (!$memberId) return 'N/A';
        return Membership::find($memberId)?->token ?? 'N/A';
    }

    protected function loadUplines()
    {
        $this->binaryUplines = $this->getBinaryUplines($this->member->id);
        $this->referralUplines = $this->getReferralUplines($this->member->id);
    }

    protected function loadCommissionData()
    {
        $this->binaryCommissionTotal = WalletTransaction::where('membership_id', $this->member->id)
            ->where('type', 'binary_commission')
            ->where('status', 'confirmed')
            ->sum('amount');
        $this->referralCommissionTotal = WalletTransaction::where('membership_id', $this->member->id)
            ->where('type', 'referral_commission')
            ->where('status', 'confirmed')
            ->sum('amount');
        $this->dailyCommissionTotal = WalletTransaction::where('membership_id', $this->member->id)
            ->where('type', 'daily_commission')
            ->where('status', 'confirmed')
            ->sum('amount');

        $this->binaryCommissionTx = WalletTransaction::where('membership_id', $this->member->id)
            ->where('type', 'binary_commission')
            ->orderBy('created_at', 'desc')
            ->limit(200)
            ->get()
            ->toArray();
        $this->referralCommissionTx = WalletTransaction::where('membership_id', $this->member->id)
            ->where('type', 'referral_commission')
            ->orderBy('created_at', 'desc')
            ->limit(200)
            ->get()
            ->toArray();
        $this->dailyCommissionTx = WalletTransaction::where('membership_id', $this->member->id)
            ->where('type', 'daily_commission')
            ->orderBy('created_at', 'desc')
            ->limit(200)
            ->get()
            ->toArray();
    }

    protected function getBinaryUplines($memberId)
    {
        $list = [];
        $current = $memberId;
        $level = 1;
        while (true) {
            $pos = BinaryTree::where('member_id', $current)->first();
            if (!$pos || !$pos->parent_id) break;
            $parent = Membership::find($pos->parent_id);
            if (!$parent) break;
            $list[] = [
                'level' => $level,
                'name' => $parent->name,
                'token' => $parent->token,
                'position' => $pos->position
            ];
            $current = $parent->id;
            $level++;
            if ($level > 10) break;
        }
        return $list;
    }

    protected function getReferralUplines($memberId)
    {
        $list = [];
        $current = $memberId;
        $level = 1;
        while (true) {
            $ref = \App\Models\ReferralTree::where('member_id', $current)->first();
            if (!$ref || !$ref->parent_id) break;
            $parent = Membership::find($ref->parent_id);
            if (!$parent) break;
            $list[] = [
                'level' => $level,
                'name' => $parent->name,
                'token' => $parent->token
            ];
            $current = $parent->id;
            $level++;
            if ($level > 10) break;
        }
        return $list;
    }

    public function setTab($tab)
    {
        if (in_array($tab, $this->validTabs)) {
            $this->activeTab = $tab;
            if ($tab === 'tree') {
                // Send tree data after tab change
                $this->dispatch('tabChanged', treeData: $this->getTreeData());
            }
        }
    }

    public function navigateToMember($memberId)
    {
        if ($memberId && !str_contains($memberId, 'empty')) {
            return redirect()->route('admin.members.view', $memberId);
        }
    }

    protected function getTreeData()
    {
        $flatData = [];
        $this->processNode($this->member->id, null, $flatData);
        return $flatData;
    }

    protected function processNode($memberId, $parentId, &$flatData)
    {
        if (!$memberId) return;

        $member = Membership::find($memberId);
        if (!$member) return;

        $flatData[] = [
            'id' => $member->id,
            'parentId' => $parentId,
            'name' => $member->name,
            'token' => $member->token,
            'status' => $member->isVerified ? 'verified' : 'pending'
        ];

        $tree = BinaryTree::where('parent_id', $memberId)->get();

        // Process left child
        $left = $tree->where('position', 'left')->first();
        if ($left) {
            $this->processNode($left->member_id, $member->id, $flatData);
        } else {
            $flatData[] = [
                'id' => "empty-left-{$member->id}",
                'parentId' => $member->id,
                'name' => 'Empty',
                'token' => '',
                'status' => 'empty'
            ];
        }

        // Process right child
        $right = $tree->where('position', 'right')->first();
        if ($right) {
            $this->processNode($right->member_id, $member->id, $flatData);
        } else {
            $flatData[] = [
                'id' => "empty-right-{$member->id}",
                'parentId' => $member->id,
                'name' => 'Empty',
                'token' => '',
                'status' => 'empty'
            ];
        }
    }

    public function render()
    {
        return view('livewire.admin.view-member');
    }

    protected function hasFirstPair($memberId)
    {
        $left = BinaryTree::where('parent_id', $memberId)->where('position', 'left')->first();
        $right = BinaryTree::where('parent_id', $memberId)->where('position', 'right')->first();
        if (!$left || !$right) {
            return false;
        }
        [$leftDepth] = $this->getDirectionalDepth($left->member_id, 'left');
        [$rightDepth] = $this->getDirectionalDepth($right->member_id, 'right');
        $maxDepth = max($leftDepth, $rightDepth);
        $minDepth = min($leftDepth, $rightDepth);
        return $maxDepth >= 2 && $minDepth >= 1;
    }

    protected function generateDailyCashback()
    {
        if (!($this->member->payment_status === 'success' || $this->member->isPaid)) {
            return;
        }
        $start = $this->member->created_at->copy()->startOfDay();
        $end = now()->copy()->startOfDay();
        $eligibleDays = min(30, $start->diffInDays($end) + 1);

        for ($i = 0; $i < $eligibleDays; $i++) {
            $date = $start->copy()->addDays($i);
            $existsForDate = WalletTransaction::where('membership_id', $this->member->id)
                ->where('type', 'daily_commission')
                ->whereDate('created_at', $date->toDateString())
                ->exists();
            if ($existsForDate) {
                continue;
            }
            $totalReceived = WalletTransaction::where('membership_id', $this->member->id)
                ->where('type', 'daily_commission')
                ->sum('amount');
            if ($totalReceived >= 480) {
                break;
            }
            WalletTransaction::create([
                'membership_id' => $this->member->id,
                'type' => 'daily_commission',
                'amount' => 16,
                'status' => 'confirmed',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
