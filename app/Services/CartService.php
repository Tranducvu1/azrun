<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getItems(): array
    {
        $cart = Session::get('cart', []);
        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $qty) {
            $product = Product::with('brand')->find($productId);
            if (!$product) {
                continue;
            }
            $price = $product->current_price;
            $lineTotal = $price * $qty;
            $items[] = [
                'product' => $product,
                'quantity' => $qty,
                'price' => $price,
                'subtotal' => $lineTotal,
            ];
            $subtotal += $lineTotal;
        }

        return compact('items', 'subtotal');
    }

    public function getTotals(?Coupon $coupon = null): array
    {
        ['items' => $items, 'subtotal' => $subtotal] = $this->getItems();

        $discount = 0;
        if ($coupon && $coupon->isValid() && $subtotal >= $coupon->min_order_amount) {
            $discount = $coupon->calculateDiscount($subtotal);
        }

        $shipping = $subtotal >= 1000000 ? 0 : 30000;
        $total = max(0, $subtotal - $discount + $shipping);

        return compact('items', 'subtotal', 'discount', 'shipping', 'total');
    }

    public function getAppliedCoupon(): ?Coupon
    {
        $code = Session::get('coupon_code');
        if (!$code) {
            return null;
        }

        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon || !$coupon->isValid()) {
            Session::forget('coupon_code');
            return null;
        }

        return $coupon;
    }
}
