@extends('layouts.admin')
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Sản phẩm</p>
        <p class="text-3xl font-black text-sport-dark mt-1">{{ $stats['products'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Đơn hàng</p>
        <p class="text-3xl font-black text-sport-dark mt-1">{{ $stats['orders'] }}</p>
        <p class="text-xs text-amber-600 mt-1">{{ $stats['pending_orders'] }} chờ xác nhận</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Khách hàng</p>
        <p class="text-3xl font-black text-sport-dark mt-1">{{ $stats['customers'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Doanh thu</p>
        <p class="text-2xl font-black text-sport-accent mt-1">{{ number_format($stats['revenue'], 0, ',', '.') }}đ</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-bold text-gray-800 mb-4">Đơn hàng gần đây</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left text-gray-500 border-b"><th class="pb-2">Mã</th><th class="pb-2">Khách</th><th class="pb-2">Tổng</th><th class="pb-2">Trạng thái</th></tr></thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr class="border-b last:border-0">
                            <td class="py-2"><a href="{{ route('admin.orders.show', $order) }}" class="text-sport-accent font-medium">{{ $order->order_code }}</a></td>
                            <td class="py-2">{{ $order->name }}</td>
                            <td class="py-2">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                            <td class="py-2"><span class="text-xs px-2 py-0.5 rounded bg-gray-100">{{ $order->status_label }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-bold text-gray-800 mb-4">Sản phẩm bán chạy</h2>
        <div class="space-y-3">
            @foreach($topProducts as $product)
                <div class="flex items-center gap-3">
                    <img src="{{ $product->thumbnail }}" class="w-10 h-10 rounded object-cover" alt="">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400">Đã bán: {{ $product->sold_count }}</p>
                    </div>
                    <span class="text-sm font-bold text-sport-accent">{{ number_format($product->current_price, 0, ',', '.') }}đ</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
