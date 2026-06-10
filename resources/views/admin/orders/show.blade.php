@extends('layouts.admin')
@section('page_title', 'Đơn hàng ' . $order->order_code)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold mb-4">Sản phẩm</h2>
            @foreach($order->items as $item)
                <div class="flex justify-between py-2 border-b last:border-0 text-sm">
                    <div>
                        <p class="font-medium">{{ $item->name }}</p>
                        <p class="text-gray-400">SL: {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}đ</p>
                    </div>
                    <span class="font-medium">{{ number_format($item->subtotal, 0, ',', '.') }}đ</span>
                </div>
            @endforeach
            <div class="mt-4 pt-4 border-t space-y-1 text-sm">
                <div class="flex justify-between"><span>Tạm tính</span><span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span></div>
                <div class="flex justify-between"><span>Giảm giá</span><span>-{{ number_format($order->discount, 0, ',', '.') }}đ</span></div>
                <div class="flex justify-between"><span>Vận chuyển</span><span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span></div>
                <div class="flex justify-between font-bold text-lg pt-2"><span>Tổng</span><span class="text-sport-accent">{{ number_format($order->total, 0, ',', '.') }}đ</span></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold mb-4">Thông tin giao hàng</h2>
            <div class="text-sm space-y-2">
                <p><strong>Người nhận:</strong> {{ $order->name }}</p>
                <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                <p><strong>Email:</strong> {{ $order->email ?? '-' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->province }}</p>
                @if($order->notes)<p><strong>Ghi chú:</strong> {{ $order->notes }}</p>@endif
            </div>
        </div>
    </div>

    <div>
        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            @csrf @method('PUT')
            <h2 class="font-bold">Cập nhật trạng thái</h2>
            <div>
                <label class="block text-sm font-medium mb-1">Trạng thái đơn</label>
                <select name="status" class="w-full px-3 py-2 border rounded-lg text-sm">
                    @foreach(['pending','confirmed','shipping','delivered','cancelled','refunded'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Thanh toán</label>
                <select name="payment_status" class="w-full px-3 py-2 border rounded-lg text-sm">
                    @foreach(['unpaid','paid','refunded'] as $s)
                        <option value="{{ $s }}" {{ $order->payment_status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <p class="text-xs text-gray-400">PTTT: {{ strtoupper($order->payment_method) }}</p>
            <button type="submit" class="w-full py-2 bg-sport-accent text-white rounded-lg font-medium">Cập nhật</button>
        </form>
        <a href="{{ route('admin.orders.index') }}" class="block mt-4 text-center text-sm text-gray-500 hover:text-sport-accent">&larr; Quay lại</a>
    </div>
</div>
@endsection
