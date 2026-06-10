<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Services\CartService;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private VNPayService $vnpay
    ) {}

    public function index()
    {
        $coupon = $this->cartService->getAppliedCoupon();
        $totals = $this->cartService->getTotals($coupon);

        return view('cart', array_merge($totals, ['coupon' => $coupon]));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;
        $cart = Session::get('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        Session::put('cart', $cart);

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);
        $cart[$request->product_id] = $request->quantity;
        Session::put('cart', $cart);

        return back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    public function remove($productId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        Session::put('coupon_code', $coupon->code);

        return back()->with('success', "Đã áp dụng mã {$coupon->code}!");
    }

    public function removeCoupon()
    {
        Session::forget('coupon_code');
        return back()->with('success', 'Đã xóa mã giảm giá.');
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('success', 'Giỏ hàng trống!');
        }

        $coupon = $this->cartService->getAppliedCoupon();
        $totals = $this->cartService->getTotals($coupon);

        return view('checkout', array_merge($totals, ['coupon' => $coupon]));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'required|string',
            'province' => 'nullable|string',
            'district' => 'nullable|string',
            'ward' => 'nullable|string',
            'payment_method' => 'required|in:cod,vnpay,momo,bank_transfer',
            'notes' => 'nullable|string',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart');
        }

        $coupon = $this->cartService->getAppliedCoupon();
        $totals = $this->cartService->getTotals($coupon);

        $orderItems = [];
        foreach ($totals['items'] as $item) {
            $orderItems[] = new OrderItem([
                'product_id' => $item['product']->id,
                'name' => $item['product']->name,
                'sku' => $item['product']->sku,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        if ($coupon && $totals['discount'] > 0) {
            $coupon->increment('used_count');
        }

        $order = Order::create([
            'order_code' => Order::generateOrderCode(),
            'user_id' => Auth::id(),
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'shipping_fee' => $totals['shipping'],
            'total' => $totals['total'],
            'coupon_id' => $coupon?->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'notes' => $request->notes,
        ]);

        $order->items()->saveMany($orderItems);

        foreach ($cart as $productId => $qty) {
            Product::where('id', $productId)->increment('sold_count', $qty);
            Product::where('id', $productId)->decrement('stock_quantity', $qty);
        }

        Session::forget('cart');
        Session::forget('coupon_code');

        if ($request->payment_method === 'vnpay' && config('vnpay.tmn_code')) {
            return redirect($this->vnpay->createPaymentUrl($order, $request->ip()));
        }

        if ($request->payment_method === 'vnpay') {
            return redirect()->route('order.success', $order->order_code)
                ->with('success', 'Đặt hàng thành công! (VNPay chưa cấu hình — dùng COD để demo)');
        }

        return redirect()->route('order.success', $order->order_code)->with('success', 'Đặt hàng thành công!');
    }

    public function orderSuccess($orderCode)
    {
        $order = Order::where('order_code', $orderCode)->with('items.product')->firstOrFail();
        return view('order-success', compact('order'));
    }
}
