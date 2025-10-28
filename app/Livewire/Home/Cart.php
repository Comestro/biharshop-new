<?php

namespace App\Livewire\Home;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Cart extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $subtotal = 0;
    public $cartItem = [];
    public $shipping;
    public $tax;

    public function mount()
    {

        // Add via ?add=ID in URL
        if (request()->has('add')) {
            $this->addToCart(request()->get('add'));
        }

        // Merge guest cart when user logs in
        if (Auth::check()) {
            $this->mergeSessionCart();
        }

        $this->loadCart();
    }

    protected function mergeSessionCart()
    {
        $sessionCart = session()->get('cart', []);
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $item) {
                $cartItem = OrderItem::firstOrCreate(
                    ['user_id' => Auth::id(), 'product_id' => $productId, 'order_id' => null],
                    ['qty' => 0, 'unit_price' => $item['price'], 'subtotal' => 0]
                );
                $cartItem->qty += $item['qty'];
                $cartItem->subtotal = $cartItem->qty * $cartItem->unit_price;
                $cartItem->save();
            }
            session()->forget('cart');
        }
    }

    public function loadCart()
    {
        if (Auth::check()) {
            $this->cartItems = OrderItem::with('product.category')
                ->where('user_id', Auth::id())
                ->whereNull('order_id')
                ->get()
                ->map(function ($item) {
                    $item->subtotal = $item->qty * $item->unit_price;
                    return $item;
                });
        } else {
            $sessionCart = session()->get('cart', []);
            $this->cartItems = collect($sessionCart)->map(function ($item) {
                return (object) [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'image' => $item['image'] ?? '',
                    'price' => $item['price'],
                    'category' => $item['category'] ?? null,
                    'qty' => $item['qty'],
                    'subtotal' => $item['qty'] * $item['price']
                ];
            });
        }

        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->total = $this->cartItems->sum('subtotal');
        $this->tax = $this->total * 0.05;
        $this->subtotal = $this->total + $this->tax;

        if ($this->subtotal > 499) {
            $this->shipping = 0;

        } else {
            $this->shipping = 50;
            $this->subtotal = $this->total + $this->tax + $this->shipping;

        }
    }

    public function addToCart($productId)
    {
        $product = Product::with('category')->find($productId);
        if (!$product)
            return;

        if (Auth::check()) {
            $cartItem = OrderItem::firstOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $productId, 'order_id' => null],
                ['qty' => 0, 'unit_price' => $product->price, 'subtotal' => 0]
            );
            $cartItem->qty += 1;
            $cartItem->subtotal = $cartItem->qty * $cartItem->unit_price;
            $cartItem->save();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['qty'] += 1;
            } else {
                $cart[$productId] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image ?? '',
                    'price' => $product->price,
                    'category' => $product->category->name ?? '',
                    'qty' => 1,
                    'subtotal' => $product->price
                ];
            }
            session()->put('cart', $cart);
        }

        $this->loadCart();
        return redirect()->route('cart');

    }

    public function updateQty($productId, $qty)
    {
        $qty = max(1, (int) $qty);

        if (Auth::check()) {
            $item = OrderItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->whereNull('order_id')
                ->first();

            if ($item) {
                $item->qty = $qty;
                $item->subtotal = $qty * $item->unit_price;
                $item->save();
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['qty'] = $qty;
                $cart[$productId]['subtotal'] = $qty * $cart[$productId]['price'];
            }
            session()->put('cart', $cart);
        }

        $this->loadCart();
    }

    public function removeItem($productId)
    {
        if (Auth::check()) {
            OrderItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->whereNull('order_id')
                ->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        $this->loadCart();
    }

    public function render()
    {
        return view('livewire.home.cart', [
            'cartItems' => $this->cartItems,
            'total' => $this->total,
        ]);
    }
}
