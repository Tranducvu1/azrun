<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class VNPayService
{
    public function createPaymentUrl(Order $order, string $ipAddress): string
    {
        $params = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => config('vnpay.tmn_code'),
            'vnp_Amount' => (int) ($order->total * 100),
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $order->order_code,
            'vnp_OrderInfo' => 'Thanh toan don hang ' . $order->order_code,
            'vnp_OrderType' => 'other',
            'vnp_Locale' => 'vn',
            'vnp_ReturnUrl' => config('vnpay.return_url'),
            'vnp_IpAddr' => $ipAddress,
            'vnp_CreateDate' => now()->format('YmdHis'),
        ];

        ksort($params);
        $hashData = http_build_query($params);
        $secureHash = hash_hmac('sha512', $hashData, config('vnpay.hash_secret'));

        return config('vnpay.url') . '?' . $hashData . '&vnp_SecureHash=' . $secureHash;
    }

    public function verifyReturn(Request $request): array
    {
        $input = $request->all();
        $secureHash = $input['vnp_SecureHash'] ?? '';
        unset($input['vnp_SecureHash'], $input['vnp_SecureHashType']);

        ksort($input);
        $hashData = http_build_query($input);
        $calculated = hash_hmac('sha512', $hashData, config('vnpay.hash_secret'));

        $isValid = hash_equals($calculated, $secureHash);
        $isSuccess = ($input['vnp_ResponseCode'] ?? '') === '00';

        return [
            'valid' => $isValid,
            'success' => $isValid && $isSuccess,
            'order_code' => $input['vnp_TxnRef'] ?? null,
            'transaction_no' => $input['vnp_TransactionNo'] ?? null,
        ];
    }
}
