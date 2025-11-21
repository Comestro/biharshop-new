<?php

namespace App\Livewire\Member;

use App\Models\WalletTransaction;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.member')]
class IncomeReport extends Component
{
    public $activeTab = 'monthly';
    public $monthly = [];
    public $daily = [];

    public function mount()
    {
        $mid = auth()->user()->membership->id;
        $this->monthly = $this->buildMonthly($mid);
        $this->daily = $this->buildDaily($mid);
    }

    private function buildMonthly($memberId)
    {
        $rows = [];
        $tx = WalletTransaction::where('membership_id', $memberId)
            ->whereIn('type', ['binary_commission', 'referral_commission', 'daily_cashback', 'retopup'])
            ->orderBy('created_at', 'asc')
            ->get(['type', 'amount', 'created_at']);

        $groups = [];
        foreach ($tx as $t) {
            $key = Carbon::parse($t->created_at)->format('Y-m');
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'date' => Carbon::parse($t->created_at)->startOfMonth()->format('d-M-Y 12:00AM'),
                    'direct_sponsor' => 0,
                    'matching' => 0,
                    'daily' => 0,
                    'everest' => 0,
                    'diamond' => 0,
                    'retopup' => 0,
                ];
            }
            if ($t->type === 'referral_commission')
                $groups[$key]['direct_sponsor'] += $t->amount;
            if ($t->type === 'binary_commission')
                $groups[$key]['matching'] += $t->amount;
            if ($t->type === 'daily_cashback')
                $groups[$key]['daily'] += $t->amount;
            if ($t->type === 'retopup')
                $groups[$key]['retopup'] += $t->amount;
        }

        $prevIncome = 0;
        $sno = 1;
        foreach ($groups as $month => $vals) {
            $gross = round($vals['direct_sponsor'] + $vals['matching'] + $vals['daily'] + $vals['everest'] + $vals['diamond'], 2);
            $tds = round($gross * 0.02, 2);
            $admin = round($gross * 0.05, 2);
            $retopup = round($vals['retopup'], 2);
            $totalDeduction = round($tds + $admin + $retopup, 2);
            $net = max(round($gross - $totalDeduction, 2), 0);
            $rows[] = [
                'sno' => $sno++,
                'payout_date' => $vals['date'],
                'everest' => $vals['everest'],
                'direct_sponsor' => $vals['direct_sponsor'],
                'matching' => $vals['matching'],
                'diamond' => $vals['diamond'],
                'daily' => $vals['daily'],
                'gross' => $gross,
                'tds' => $tds,
                'admin' => $admin,
                'retopup' => $vals['retopup'],
                'total_deduction' => $totalDeduction,
                'previous_income' => $prevIncome,
                'net_income' => $net,
                'closing_income' => $prevIncome + $net,
            ];
            $prevIncome += $net;
        }
        return $rows;
    }

    private function buildDaily($memberId)
    {
        $rows = [];
        $tx = WalletTransaction::where('membership_id', $memberId)
            ->whereIn('type', ['binary_commission', 'referral_commission', 'daily_cashback', 'retopup'])
            ->whereDate('created_at', '>=', Carbon::now()->subDays(60)->toDateString())
            ->orderBy('created_at', 'asc')
            ->get(['type', 'amount', 'created_at']);

        $groups = [];
        foreach ($tx as $t) {
            $key = Carbon::parse($t->created_at)->format('Y-m-d');
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'date' => Carbon::parse($t->created_at)->format('d-M-Y 12:00AM'),
                    'direct_sponsor' => 0,
                    'matching' => 0,
                    'daily' => 0,
                    'retopup' => 0,
                    'everest' => 0,
                    'diamond' => 0,
                ];
            }
            if ($t->type === 'referral_commission')
                $groups[$key]['direct_sponsor'] += $t->amount;
            if ($t->type === 'binary_commission')
                $groups[$key]['matching'] += $t->amount;
            if ($t->type === 'daily_cashback')
                $groups[$key]['daily'] += $t->amount;
            if ($t->type === 'retopup')
                $groups[$key]['retopup'] += $t->amount;
        }

        $sno = 1;
        foreach ($groups as $day => $vals) {
            $gross = round($vals['direct_sponsor'] + $vals['matching'] + $vals['daily'] + $vals['everest'] + $vals['diamond'], 2);
            $tds = round($gross * 0.02, 2);
            $admin = round($gross * 0.05, 2);
            $retopup = 0.00;
            $totalDeduction = round($tds + $admin + $retopup, 2);
            $net = max(round($gross - $totalDeduction, 2), 0);
            $rows[] = [
                'sno' => $sno++,
                'payout_date' => $vals['date'],
                'everest' => $vals['everest'],
                'direct_sponsor' => $vals['direct_sponsor'],
                'matching' => $vals['matching'],
                'diamond' => $vals['diamond'],
                'daily' => $vals['daily'],
                'gross' => $gross,
                'tds' => $tds,
                'admin' => $admin,
                'retopup' => $vals['retopup'],
                'total_deduction' => $totalDeduction,
                'net_income' => $net,
            ];
        }
        return $rows;
    }

    public function render()
    {
        return view('livewire.member.income-report');
    }
}

