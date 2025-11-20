<?php
namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Membership;
use App\Models\Product;
use App\Models\Category;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use App\Models\BinaryTree as BinaryTreeModel;
use Illuminate\Support\Facades\Artisan;
#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';
    public $selectedMembership = null;
    public $showModal = false;

    public function members()
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
                return $q->where(function($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                          ->orWhere('email', 'like', '%'.$this->search.'%')
                          ->orWhere('mobile', 'like', '%'.$this->search.'%')
                          ->orWhere('token', 'like', '%'.$this->search.'%');
                });
            });

        return view('livewire.admin.members', [
            'members' => $query->paginate(10)
        ]);
    }

    public function showMemberDetails($id)
    {
        $this->selectedMembership = Membership::find($id);
        $this->showModal = true;
    }

    public function approveMember()
    {
        if ($this->selectedMembership) {
            $this->selectedMembership->update([
                'isVerified' => true
            ]);
            $this->showModal = false;
            session()->flash('message', 'Member verified successfully.');
        }
    }

    public function render()
    {
        $totalMembers = Membership::count();
        $verifiedMembers = Membership::where('isVerified', true)->count();
        $paidMembers = Membership::where('isPaid', true)->count();
        $unpaidMembers = Membership::where('isPaid', false)->count();
        $pendingVerifications = Membership::where('isPaid', true)->where('isVerified', false)->count();
        $todayNewMembers = Membership::whereDate('created_at', now()->toDateString())->count();

        $totalProducts = Product::count();
        $totalCategories = Category::count();

        $walletCreditsSum = WalletTransaction::where('status', 'confirmed')->sum('amount');
        $totalEarnings = WalletTransaction::where('status', 'confirmed')->sum('amount');
        $totalWalletGenerated = $walletCreditsSum;
        $walletCreditsToday = WalletTransaction::where('status', 'confirmed')->whereDate('created_at', now()->toDateString())->sum('amount');
        $walletCreditsTodayCount = WalletTransaction::where('status', 'confirmed')->whereDate('created_at', now()->toDateString())->count();

        $withdrawPendingCount = Withdrawal::where('status', 'pending')->count();
        $withdrawPendingSum = Withdrawal::where('status', 'pending')->sum('amount');
        $withdrawApprovedSum = Withdrawal::where('status', 'approved')->sum('amount');
        $withdrawTotalSum = Withdrawal::sum('amount');

        $binaryLinks = BinaryTreeModel::count();

        $topReferrers = Membership::select('id', 'name', 'token')
            ->withCount(['referrals'])
            ->orderBy('referrals_count', 'desc')
            ->limit(5)
            ->get();

        $recentMembers = Membership::orderBy('created_at', 'desc')->limit(10)->get();
        $recentWithdrawals = Withdrawal::with('membership')->orderBy('created_at', 'desc')->limit(10)->get();
        $recentTransactions = WalletTransaction::with('membership')->orderBy('created_at', 'desc')->limit(10)->get();

        $stats = [
            'total_members' => $totalMembers,
            'verified_members' => $verifiedMembers,
            'paid_members' => $paidMembers,
            'unpaid_members' => $unpaidMembers,
            'pending_verifications' => $pendingVerifications,
            'today_new_members' => $todayNewMembers,
            'total_products' => $totalProducts,
            'total_categories' => $totalCategories,
            'wallet_credits_sum' => $walletCreditsSum,
            'total_earnings' => $totalEarnings,
            'total_wallet_generated' => $totalWalletGenerated,
            'wallet_credits_today' => $walletCreditsToday,
            'wallet_credits_today_count' => $walletCreditsTodayCount,
            'withdraw_pending_count' => $withdrawPendingCount,
            'withdraw_pending_sum' => $withdrawPendingSum,
            'withdraw_approved_sum' => $withdrawApprovedSum,
            'withdraw_total_sum' => $withdrawTotalSum,
            'binary_links' => $binaryLinks,
        ];

        return view('livewire.admin.dashboard.index', [
            'stats' => $stats,
            'topReferrers' => $topReferrers,
            'recentMembers' => $recentMembers,
            'recentWithdrawals' => $recentWithdrawals,
            'recentTransactions' => $recentTransactions,
        ]);
    }

    public function refreshCommissions()
    {
        Artisan::call('wallet:daily-commission');
        Artisan::call('wallet:recompute-binary-referral');
        $this->dispatch('$refresh');
    }
}
