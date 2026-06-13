@extends('layouts.app')
@section('title', 'Giỏ Hàng — AZRun')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 md:py-12">

    {{-- Steps --}}
    <div class="flex items-center justify-center gap-2 mb-10">
        @foreach(['Giỏ hàng', 'Thanh toán', 'Hoàn tất'] as $i => $step)
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $i === 0 ? 'bg-brand-black text-white' : 'bg-brand-surface text-gray-400' }}">{{ $i + 1 }}</span>
                <span class="text-sm font-semibold {{ $i === 0 ? 'text-brand-black' : 'text-gray-400' }} hidden sm:inline">{{ $step }}</span>
            </div>
            @if($i < 2)<div class="w-8 h-px bg-gray-200"></div>@endif
        @endforeach
    </div>

    <h1 class="font-display text-3xl font-bold text-brand-black mb-8">Giỏ hàng của bạn</h1>

    @if(empty($items))
        <div class="text-center py-20 bg-white rounded-3xl shadow-card">
            <div class="w-24 h-24 mx-auto bg-brand-surface rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h2 class="font-display text-2xl font-bold text-brand-black">Giỏ hàng trống</h2>
            <p class="text-gray-500 mt-2 mb-8">Khám phá bộ sưu tập giày chạy & outdoor mới nhất</p>
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-brand-black text-white font-bold rounded-2xl hover:bg-accent transition-colors">
                Bắt đầu mua sắm
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $item)
                    <div class="bg-white rounded-2xl shadow-card p-4 md:p-5 flex gap-4 md:gap-5">
                        <a href="{{ route('product.show', $item['product']->slug) }}" class="shrink-0">
                            <img src="{{ $item['product']->displayThumbnail() }}" alt="{{ $item['product']->name }}" class="w-24 h-24 md:w-28 md:h-28 rounded-2xl object-cover">
                        </a>
                        <div class="flex-1 min-w-0 flex flex-col">
                            <a href="{{ route('product.show', $item['product']->slug) }}" class="font-semibold text-brand-black hover:text-accent line-clamp-2 transition-colors">{{ $item['product']->name }}</a>
                            @if($item['product']->brand)
                                <p class="text-[10px] font-bold tracking-widest uppercase text-gray-400 mt-1">{{ $item['product']->brand->name }}</p>
                            @endif
                            <p class="text-lg font-bold text-accent mt-auto">{{ number_format($item['price'], 0, ',', '.') }}₫</p>
                        </div>
                        <div class="flex flex-col items-end justify-between shrink-0">
                            <a href="{{ route('cart.remove', $item['product']->id) }}" class="text-gray-300 hover:text-red-500 transition-colors p-1" onclick="return confirm('Xóa sản phẩm?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                            <form action="{{ route('cart.update') }}" method="POST" class="flex items-center bg-brand-surface rounded-xl p-0.5">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                <button type="submit" onclick="this.form.quantity.value=Math.max(1,parseInt(this.form.quantity.value)-1)" class="w-9 h-9 flex items-center justify-center font-bold text-gray-500 hover:text-accent rounded-lg">−</button>
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-10 text-center bg-transparent font-bold text-sm focus:outline-none">
                                <button type="submit" onclick="this.form.quantity.value=parseInt(this.form.quantity.value)+1" class="w-9 h-9 flex items-center justify-center font-bold text-gray-500 hover:text-accent rounded-lg">+</button>
                            </form>
                            <p class="font-bold text-brand-black text-sm">{{ number_format($item['subtotal'], 0, ',', '.') }}₫</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-card p-6 sticky top-24">
                    <h2 class="font-display font-bold text-lg mb-5">Tóm tắt đơn hàng</h2>

                    {{-- Freeship progress --}}
                    @php $freeshipTarget = 1000000; $progress = min(100, ($subtotal / $freeshipTarget) * 100); @endphp
                    <div class="mb-5 p-4 bg-brand-surface rounded-xl">
                        @if($subtotal >= $freeshipTarget)
                            <p class="text-sm font-semibold text-emerald-600">🎉 Bạn được miễn phí vận chuyển!</p>
                        @else
                            <p class="text-xs text-gray-600 mb-2">Mua thêm <strong class="text-accent">{{ number_format($freeshipTarget - $subtotal, 0, ',', '.') }}₫</strong> để freeship</p>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-accent rounded-full transition-all" style="width: {{ $progress }}%"></div>
                            </div>
                        @endif
                    </div>

                    @if(!isset($coupon) || !$coupon)
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2 mb-5">
                            @csrf
                            <input type="text" name="coupon_code" placeholder="Mã giảm giá" class="flex-1 px-4 py-2.5 bg-brand-surface border-0 rounded-xl text-sm uppercase font-semibold focus:ring-2 focus:ring-accent/20">
                            <button type="submit" class="px-4 py-2.5 bg-brand-black text-white text-sm font-bold rounded-xl hover:bg-accent transition-colors">OK</button>
                        </form>
                    @elseif(($discount ?? 0) > 0)
                        <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl p-3 mb-5 text-sm">
                            <span class="text-emerald-700 font-semibold">{{ $coupon->code }} · -{{ number_format($discount, 0, ',', '.') }}₫</span>
                            <a href="{{ route('cart.coupon.remove') }}" class="text-red-500 text-xs font-bold">Xóa</a>
                        </div>
                    @endif

                    <div class="space-y-3 text-sm border-t border-gray-100 pt-5">
                        <div class="flex justify-between"><span class="text-gray-500">Tạm tính</span><span class="font-semibold">{{ number_format($subtotal, 0, ',', '.') }}₫</span></div>
                        @if(($discount ?? 0) > 0)
                            <div class="flex justify-between text-emerald-600"><span>Giảm giá</span><span class="font-semibold">-{{ number_format($discount, 0, ',', '.') }}₫</span></div>
                        @endif
                        <div class="flex justify-between"><span class="text-gray-500">Vận chuyển</span><span class="font-semibold {{ $shipping === 0 ? 'text-emerald-600' : '' }}">{{ $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . '₫' }}</span></div>
                        <div class="flex justify-between pt-3 border-t border-gray-100">
                            <span class="font-display font-bold text-lg">Tổng</span>
                            <span class="font-display font-bold text-2xl text-accent">{{ number_format($total, 0, ',', '.') }}₫</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}" class="block w-full mt-6 py-4 bg-accent text-white text-center font-bold rounded-2xl hover:bg-accent-dark transition-all hover:shadow-glow shimmer">
                        Thanh toán
                    </a>
                    <a href="{{ route('shop') }}" class="block w-full mt-3 py-3 text-center text-sm font-semibold text-gray-500 hover:text-accent transition-colors">← Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
