<?php

namespace App\Livewire\Home;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('components.layouts.app')]

class Checkout extends Component
{
    public $isOpenAddModel = false;
    public $selected = null;
    public $selectedAddressId = null;
    public $continueToPaymentSection = false;
    public $paymentMethod = "";


    public $address_line1;
    public $near_by;
    public $city;
    public $state;
    public $country;
    public $postal_code;
    public $phone;


    public $cartItem = [];
    public $subtotal = 0;
    public $shipping = 0;
    public $tax = 0;
    public $total = 0;
    public $couponDiscount = 0;


    // couponApplied
    public $couponApplied = '';
    public $couponError = '';
    public $couponCode = '';
    public $coupon_id = '';


    protected $rules = [
        'address_line1' => 'required|string|max:255',
        'near_by' => 'nullable|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'country' => 'required|string|max:100',
        'postal_code' => 'required|string|max:20',
        'phone' => 'nullable|string|max:20',
    ];
    public function addAddress()
    {
        $this->validate();

        Address::create([
            'user_id' => Auth::id(),
            'status' => 'active',
            'address_line1' => $this->address_line1,
            'near_by' => $this->near_by,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
        ]);

        $this->reset(['address_line1', 'near_by', 'city', 'state', 'country', 'postal_code', 'phone', 'isOpenAddModel']);

    }
    public function mount()
    {

        $this->calculateTotals();
    }

    public function selectAddress($id)
    {
        $this->selectedAddressId = $id;
    }
    public function openAddModel()
    {
        $this->isOpenAddModel = true;
    }
    public function closeAddModel()
    {
        $this->isOpenAddModel = false;
    }
    public function nullAddressId()
    {
        $this->selectedAddressId = null;
        $this->paymentMethod = "";
        $this->continueToPaymentSection = false;


    }
    public function continueToPayment()
    {
        $this->continueToPaymentSection = true;
    }
    public function selectPaymentMethod($method)
    {
        $this->paymentMethod = $method;
    }
    public function applyCouponFromOrderSummery($code){
        $this->couponCode = $code;
        $this->applyCoupon();
    }
    public function applyCoupon()
    {
        $code = strtoupper(trim($this->couponCode));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            $this->couponError = 'Invalid coupon code';
            $this->couponApplied = false;
            $this->couponDiscount = 0;
            return;
        }

        if ($coupon->status !== 'active') {
            $this->couponError = 'This coupon is inactive';
            return;
        }
        if ($coupon->used_count >= $coupon->usage_limit) {
            $this->couponError = 'This coupon is inactive';
            return;
        }

        $today = Carbon::today();
        if (
            ($coupon->valid_from && $today->lt(Carbon::parse($coupon->valid_from))) ||
            ($coupon->valid_until && $today->gt(Carbon::parse($coupon->valid_until)))
        ) {
            $this->couponError = 'This coupon is expired or not yet valid';
            return;
        }

        if ($coupon->min_order_amount && $this->subtotal < $coupon->min_order_amount) {
            $this->couponError = "Minimum order ₹{$coupon->min_order_amount} required for this coupon";
            return;
        }

        if ($coupon->discount_type === 'percentage') {
            $discount = round($this->subtotal * ($coupon->discount_value / 100), 2);
            if ($coupon->max_discount_amount) {
                $discount = min($discount, $coupon->max_discount_amount);
            }
        } else {
            $discount = $coupon->discount_value;
        }

        $this->couponDiscount = $discount;
        $this->couponApplied = true;
        $this->couponError = '';
        $this->calculateTotals();
    }

    public function removeCoupon()
    {
        $this->couponCode = '';
        $this->couponDiscount = 0;
        $this->couponApplied = false;
        $this->couponError = '';
        $this->calculateTotals();

    }
    private function calculateTotals()
    {
        $this->subtotal = 0;
        $this->cartItem = OrderItem::where('user_id', Auth::id())->where('order_id', null)->get();

        foreach ($this->cartItem as $item) {
            $this->subtotal += $item['unit_price'] * $item['qty'];
        }
        if ($this->subtotal > 499) {
            $this->shipping = 0;
        } else {
            $this->shipping = 50;
        }
        $this->tax = $this->subtotal * 0.05;
        $this->total = $this->subtotal + $this->tax + $this->shipping - $this->couponDiscount;
    }
    public function placeOrder()
    {
        $coupon = Coupon::where('code', $this->couponCode)->first();
        $coupon_id = $coupon->id ?? null;
        if ($coupon) {
            $coupon->used_count += 1;
            $coupon->save();

        }
        Order::create([
            'user_id' => Auth::id(),
            'coupon_id' => $coupon_id,
            'order_number' => 'ORD-' . strtoupper(string: uniqid()),
            'subtotal' => $this->subtotal,
            'discount' => 0,
            'shipping_cost' => $this->shipping,
            'total_amount' => $this->total,
            'address_id' => $this->selectedAddressId,
            'payment_method' => $this->paymentMethod,
            'payment_id' => 0,
            'finel_address' => $this->selectedAddressId,
        ]);
        return redirect()->route('ordersuccess');
    }
    public function render()
    {
        if ($this->selectedAddressId) {
            $addresses = Address::where('id', $this->selectedAddressId)->get();
        } else {
            $addresses = Address::where('user_id', Auth::id())->get();
        }
        $cartItem = OrderItem::where('user_id', Auth::id())->where('order_id', null)->get();
        $this->cartItem = $cartItem;

        $today = Carbon::today();

        $allCoupons = Coupon::where('status', 'active')
            ->whereDate('valid_from', '<=', $today)
            ->whereDate('valid_until', '>=', $today)
            ->get();
        return view('livewire.home.checkout', compact('addresses', 'cartItem','allCoupons'));
    }
}
