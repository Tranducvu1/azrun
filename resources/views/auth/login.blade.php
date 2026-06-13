@extends('layouts.app')
@section('title', 'Đăng Nhập — AZRun')

@section('content')
<div class="min-h-[80vh] grid lg:grid-cols-2">
    {{-- Brand side --}}
    <div class="hidden lg:flex flex-col justify-between bg-brand-black text-white p-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-40" style="background: radial-gradient(circle at 20% 80%, rgba(255,69,0,0.4) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(200,255,0,0.15) 0%, transparent 40%);"></div>
        <div class="relative">
            <a href="/" class="font-display text-2xl font-bold">SPORT<span class="text-accent">SHOP</span></a>
        </div>
        <div class="relative">
            <p class="text-lime text-xs font-bold tracking-[0.2em] uppercase mb-4">Runner community</p>
            <h2 class="font-display text-4xl xl:text-5xl font-bold leading-tight">Join the crew.<br>Run further.</h2>
            <p class="text-gray-500 mt-4 max-w-sm">Tích điểm mỗi đơn hàng · Ưu đãi độc quyền · Theo dõi đơn dễ dàng</p>
        </div>
        <div class="relative flex gap-8">
            <div><p class="font-display text-2xl font-bold text-lime">15K+</p><p class="text-xs text-gray-500">Runner</p></div>
            <div><p class="font-display text-2xl font-bold">4.9★</p><p class="text-xs text-gray-500">Rating</p></div>
        </div>
    </div>

    {{-- Form --}}
    <div class="flex items-center justify-center p-6 md:p-12 bg-brand-cream">
        <div class="w-full max-w-md">
            <div class="lg:hidden mb-8">
                <a href="/" class="font-display text-2xl font-bold text-brand-black">SPORT<span class="text-accent">SHOP</span></a>
            </div>
            <h1 class="font-display text-3xl font-bold text-brand-black mb-2">Chào mừng trở lại</h1>
            <p class="text-gray-500 text-sm mb-8">Đăng nhập để theo dõi đơn hàng và nhận ưu đãi</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-2xl mb-6">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-accent focus:ring-4 focus:ring-accent/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Mật khẩu</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-accent focus:ring-4 focus:ring-accent/10 transition-all">
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-accent focus:ring-accent">
                    Ghi nhớ đăng nhập
                </label>
                <button type="submit" class="w-full py-4 bg-brand-black text-white font-bold rounded-2xl hover:bg-accent transition-all hover:shadow-glow">
                    Đăng nhập
                </button>
            </form>

            <div class="mt-6 p-4 bg-white rounded-2xl border border-gray-100 text-xs text-gray-500">
                <p class="font-bold text-brand-black mb-1">Demo</p>
                <p>Admin: admin@sportshop.vn / password</p>
                <p>Khách: customer@test.com / password</p>
            </div>

            <p class="text-center text-sm text-gray-500 mt-8">
                Chưa có tài khoản? <a href="{{ route('register') }}" class="text-accent font-bold hover:underline">Đăng ký miễn phí</a>
            </p>
        </div>
    </div>
</div>
@endsection
