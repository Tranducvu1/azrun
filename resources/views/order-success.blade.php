@extends('layouts.app')
@section('title', 'Đặt Hàng Thành Công — SportShop')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12 md:py-20 text-center">

    <div class="flex items-center justify-center gap-2 mb-12">
        @foreach(['Giỏ hàng', 'Thanh toán', 'Hoàn tất'] as $i => $step)
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-brand-black text-white">✓</span>
                <span class="text-sm font-semibold text-brand-black hidden sm:inline">{{ $step }}</span>
            </div>
            @if($i < 2)<div class="w-8 h-px bg-accent"></div>@endif
        @endforeach
    </div>

    <div class="w-24 h-24 mx-auto bg-lime/20 rounded-full flex items-center justify-center mb-6 animate-float">
        <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    </div>

    <h1 class="font-display text-3xl md:text-4xl font-bold text-brand-black mb-2">Đặt hàng thành công! 🎉</h1>
    <p class="text-gray-500 mb-2">Cảm ơn bạn đã tin tưởng SportShop</p>
    <p class="text-sm text-gray-400 mb-8">Mã đơn hàng: <span class="font-display font-bold text-accent text-xl">{{ $order->order_code }}</span></p>

    <div class="bg-white rounded-3xl shadow-card p-6 md:p-8 text-left mb-8">
        <div class="grid sm:grid-cols-2 gap-4 text-sm mb-6">
            <div class="p-4 bg-brand-surface rounded-xl">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Người nhận</p>
                <p class="font-semibold text-brand-black">{{ $order->name }}</p>
                <p class="text-gray-500 mt-1">{{ $order->phone }}</p>
            </div>
            <div class="p-4 bg-brand-surface rounded-xl">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Giao đến</p>
                <p class="font-semibold text-brand-black">{{ $order->address }}</p>
            </div>
            <div class="p-4 bg-brand-surface rounded-xl">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Thanh toán</p>
                <p class="font-semibold text-brand-black uppercase">{{ $order->payment_method }}</p>
            </div>
            <div class="p-4 bg-brand-surface rounded-xl">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Trạng thái</p>
                <p class="font-semibold text-amber-600">{{ $order->status_label }}</p>
            </div>
        </div>

        <h3 class="font-display font-bold text-brand-black mb-4">Chi tiết đơn</h3>
        @foreach($order->items as $item)
            <div class="flex justify-between py-3 border-b border-gray-100 last:border-0 text-sm">
                <div>
                    <p class="font-semibold text-brand-black">{{ $item->name }}</p>
                    <p class="text-gray-400">x{{ $item->quantity }}</p>
                </div>
                <span class="font-bold">{{ number_format($item->subtotal, 0, ',', '.') }}₫</span>
            </div>
        @endforeach
        <div class="flex justify-between pt-4 mt-2">
            <span class="font-display font-bold text-lg">Tổng cộng</span>
            <span class="font-display font-bold text-2xl text-accent">{{ number_format($order->total, 0, ',', '.') }}₫</span>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('account') }}" class="px-8 py-4 border-2 border-brand-black text-brand-black font-bold rounded-2xl hover:bg-brand-black hover:text-white transition-colors">Theo dõi đơn hàng</a>
        <a href="{{ route('shop') }}" class="px-8 py-4 bg-accent text-white font-bold rounded-2xl hover:bg-accent-dark transition-all hover:shadow-glow">Tiếp tục mua sắm</a>
    </div>
</div>
@endsection
