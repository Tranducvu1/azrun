@extends('layouts.app')
@section('title', 'Thanh Toán — AZRun')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 md:py-12">

    <div class="flex items-center justify-center gap-2 mb-10">
        @foreach(['Giỏ hàng', 'Thanh toán', 'Hoàn tất'] as $i => $step)
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $i <= 1 ? 'bg-brand-black text-white' : 'bg-brand-surface text-gray-400' }}">{{ $i + 1 }}</span>
                <span class="text-sm font-semibold {{ $i <= 1 ? 'text-brand-black' : 'text-gray-400' }} hidden sm:inline">{{ $step }}</span>
            </div>
            @if($i < 2)<div class="w-8 h-px bg-gray-200"></div>@endif
        @endforeach
    </div>

    <h1 class="font-display text-3xl font-bold text-brand-black mb-8">Thanh toán</h1>

    <form action="{{ route('order.place') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-card p-6 md:p-8">
                <h2 class="font-display font-bold text-lg mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-black text-white rounded-lg flex items-center justify-center text-sm">1</span>
                    Thông tin giao hàng
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Họ và tên *</label>
                        <input type="text" name="name" value="{{ auth()->user()->name ?? old('name') }}" required class="w-full px-4 py-3 bg-brand-surface border-0 rounded-xl text-sm focus:ring-2 focus:ring-accent/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Số điện thoại *</label>
                        <input type="tel" name="phone" value="{{ auth()->user()->phone ?? old('phone') }}" required class="w-full px-4 py-3 bg-brand-surface border-0 rounded-xl text-sm focus:ring-2 focus:ring-accent/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email ?? old('email') }}" class="w-full px-4 py-3 bg-brand-surface border-0 rounded-xl text-sm focus:ring-2 focus:ring-accent/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Tỉnh/Thành</label>
                        <input type="text" name="province" value="{{ old('province') }}" class="w-full px-4 py-3 bg-brand-surface border-0 rounded-xl text-sm focus:ring-2 focus:ring-accent/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Quận/Huyện</label>
                        <input type="text" name="district" value="{{ old('district') }}" class="w-full px-4 py-3 bg-brand-surface border-0 rounded-xl text-sm focus:ring-2 focus:ring-accent/20">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Địa chỉ chi tiết *</label>
                        <input type="text" name="address" value="{{ old('address') }}" required placeholder="Số nhà, tên đường..." class="w-full px-4 py-3 bg-brand-surface border-0 rounded-xl text-sm focus:ring-2 focus:ring-accent/20">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Ghi chú</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-3 bg-brand-surface border-0 rounded-xl text-sm focus:ring-2 focus:ring-accent/20" placeholder="Giao giờ hành chính, gọi trước khi giao...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card p-6 md:p-8">
                <h2 class="font-display font-bold text-lg mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-brand-black text-white rounded-lg flex items-center justify-center text-sm">2</span>
                    Phương thức thanh toán
                </h2>
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach([
                        ['value' => 'cod', 'label' => 'COD', 'desc' => 'Thanh toán khi nhận hàng', 'icon' => '💵', 'checked' => true],
                        ['value' => 'vnpay', 'label' => 'VNPay', 'desc' => 'Thẻ ATM, Visa, QR', 'icon' => '🏦'],
                        ['value' => 'momo', 'label' => 'MoMo', 'desc' => 'Ví điện tử MoMo', 'icon' => '📱'],
                        ['value' => 'bank_transfer', 'label' => 'Chuyển khoản', 'desc' => 'Ngân hàng nội địa', 'icon' => '🏧'],
                    ] as $pm)
                        <label class="relative flex items-start gap-4 p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-accent/30 has-[:checked]:border-accent has-[:checked]:bg-accent/5 transition-all">
                            <input type="radio" name="payment_method" value="{{ $pm['value'] }}" {{ ($pm['checked'] ?? false) ? 'checked' : '' }} class="mt-1 text-accent focus:ring-accent">
                            <div>
                                <span class="text-2xl">{{ $pm['icon'] }}</span>
                                <p class="font-bold text-brand-black mt-1">{{ $pm['label'] }}</p>
                                <p class="text-xs text-gray-500">{{ $pm['desc'] }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card p-6">
                <h2 class="font-display font-bold text-lg mb-4">Mã giảm giá</h2>
                @if(isset($coupon) && $coupon)
                    <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                        <span class="text-emerald-700 font-semibold">{{ $coupon->code }} · -{{ number_format($discount ?? 0, 0, ',', '.') }}₫</span>
                        <a href="{{ route('cart.coupon.remove') }}" class="text-red-500 text-xs font-bold">Xóa</a>
                    </div>
                @else
                    <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="coupon_code" placeholder="WELCOME10, RUN50K..." class="flex-1 px-4 py-3 bg-brand-surface border-0 rounded-xl text-sm uppercase font-semibold focus:ring-2 focus:ring-accent/20">
                        <button type="submit" class="px-6 py-3 bg-brand-black text-white font-bold rounded-xl hover:bg-accent transition-colors">Áp dụng</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-card p-6 sticky top-24">
                <h2 class="font-display font-bold text-lg mb-5">Đơn hàng ({{ count($items) }})</h2>
                <div class="space-y-4 mb-5 max-h-64 overflow-y-auto">
                    @foreach($items as $item)
                        <div class="flex gap-3">
                            <img src="{{ $item['product']->displayThumbnail() }}" class="w-14 h-14 rounded-xl object-cover shrink-0" alt="{{ $item['product']->name }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-brand-black truncate">{{ $item['product']->name }}</p>
                                <p class="text-xs text-gray-400">x{{ $item['quantity'] }}</p>
                            </div>
                            <span class="text-sm font-bold shrink-0">{{ number_format($item['subtotal'], 0, ',', '.') }}₫</span>
                        </div>
                    @endforeach
                </div>
                <div class="space-y-3 text-sm border-t border-gray-100 pt-5">
                    <div class="flex justify-between"><span class="text-gray-500">Tạm tính</span><span>{{ number_format($subtotal, 0, ',', '.') }}₫</span></div>
                    @if(($discount ?? 0) > 0)
                        <div class="flex justify-between text-emerald-600"><span>Giảm giá</span><span>-{{ number_format($discount, 0, ',', '.') }}₫</span></div>
                    @endif
                    <div class="flex justify-between"><span class="text-gray-500">Vận chuyển</span><span class="{{ $shipping === 0 ? 'text-emerald-600 font-semibold' : '' }}">{{ $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . '₫' }}</span></div>
                    <div class="flex justify-between pt-3 border-t">
                        <span class="font-display font-bold text-lg">Tổng</span>
                        <span class="font-display font-bold text-2xl text-accent">{{ number_format($total, 0, ',', '.') }}₫</span>
                    </div>
                </div>
                <button type="submit" class="w-full mt-6 py-4 bg-accent text-white font-bold rounded-2xl hover:bg-accent-dark transition-all hover:shadow-glow shimmer">
                    Đặt hàng ngay
                </button>
                <p class="text-[10px] text-gray-400 text-center mt-3">Bằng việc đặt hàng, bạn đồng ý với điều khoản sử dụng</p>
            </div>
        </div>
    </form>
</div>
@endsection
