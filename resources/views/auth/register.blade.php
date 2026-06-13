@extends('layouts.app')
@section('title', 'Đăng Ký — AZRun')

@section('content')
<div class="min-h-[80vh] grid lg:grid-cols-2">
    <div class="hidden lg:flex flex-col justify-center bg-lime p-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-lime via-emerald-200 to-teal-200"></div>
        <div class="relative">
            <h2 class="font-display text-4xl xl:text-5xl font-bold text-brand-black leading-tight">Tạo tài khoản.<br>Nhận ưu đãi 10%.</h2>
            <p class="text-brand-black/60 mt-4 max-w-sm">Đăng ký ngay — mã WELCOME10 giảm 10% đơn đầu tiên</p>
            <ul class="mt-8 space-y-3">
                @foreach(['Theo dõi đơn hàng realtime', 'Lưu địa chỉ giao hàng', 'Viết đánh giá sản phẩm'] as $benefit)
                    <li class="flex items-center gap-2 text-brand-black font-medium text-sm">
                        <span class="w-5 h-5 bg-brand-black text-lime rounded-full flex items-center justify-center text-xs">✓</span>
                        {{ $benefit }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="flex items-center justify-center p-6 md:p-12 bg-brand-cream">
        <div class="w-full max-w-md">
            <div class="lg:hidden mb-8">
                <a href="/" class="font-display text-2xl font-bold">SPORT<span class="text-accent">SHOP</span></a>
            </div>
            <h1 class="font-display text-3xl font-bold text-brand-black mb-2">Tạo tài khoản</h1>
            <p class="text-gray-500 text-sm mb-8">Miễn phí — chỉ mất 30 giây</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-2xl mb-6">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf
                @foreach([
                    ['name' => 'name', 'label' => 'Họ và tên', 'type' => 'text', 'required' => true],
                    ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                    ['name' => 'phone', 'label' => 'Số điện thoại', 'type' => 'tel', 'required' => false],
                    ['name' => 'password', 'label' => 'Mật khẩu', 'type' => 'password', 'required' => true],
                    ['name' => 'password_confirmation', 'label' => 'Xác nhận mật khẩu', 'type' => 'password', 'required' => true],
                ] as $field)
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">{{ $field['label'] }}</label>
                        <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" value="{{ old($field['name']) }}" {{ ($field['required'] ?? false) ? 'required' : '' }}
                               class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-accent focus:ring-4 focus:ring-accent/10 transition-all">
                    </div>
                @endforeach
                <button type="submit" class="w-full py-4 bg-brand-black text-white font-bold rounded-2xl hover:bg-accent transition-all hover:shadow-glow mt-2">
                    Tạo tài khoản
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-8">
                Đã có tài khoản? <a href="{{ route('login') }}" class="text-accent font-bold hover:underline">Đăng nhập</a>
            </p>
        </div>
    </div>
</div>
@endsection
