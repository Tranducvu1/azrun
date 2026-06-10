<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\VNPayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private VNPayService $vnpay) {}

    public function vnpayReturn(Request $request)
    {
        $result = $this->vnpay->verifyReturn($request);

        if (!$result['valid'] || !$result['order_code']) {
            return redirect()->route('home')->with('success', 'Thanh toán không hợp lệ.');
        }

        $order = Order::where('order_code', $result['order_code'])->firstOrFail();

        if ($result['success']) {
            $order->update(['payment_status' => 'paid', 'status' => 'confirmed']);
            return redirect()->route('order.success', $order->order_code)
                ->with('success', 'Thanh toán VNPay thành công!');
        }

        $order->update(['payment_status' => 'unpaid', 'status' => 'cancelled']);
        return redirect()->route('cart')->with('success', 'Thanh toán VNPay thất bại hoặc đã huỷ.');
    }

    public function vnpayIpn(Request $request)
    {
        $result = $this->vnpay->verifyReturn($request);

        if ($result['valid'] && $result['order_code']) {
            $order = Order::where('order_code', $result['order_code'])->first();
            if ($order && $result['success']) {
                $order->update(['payment_status' => 'paid', 'status' => 'confirmed']);
            }
        }

        return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
    }
}
