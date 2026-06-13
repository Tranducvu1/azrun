@extends('layouts.app')
@section('title', 'Tài Khoản — AZRun')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 md:py-12">
    <h1 class="font-display text-3xl font-bold text-brand-black mb-8">Tài khoản của tôi</h1>

    <div class="grid lg:grid-cols-4 gap-8">
        <aside class="bg-white rounded-2xl shadow-card p-6 h-fit">
            <div class="text-center mb-6">
                <div class="w-20 h-20 mx-auto bg-brand-black text-lime rounded-2xl flex items-center justify-center text-3xl font-display font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <p class="font-display font-bold text-lg mt-4 text-brand-black">{{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                @if(auth()->user()->phone)
                    <p class="text-sm text-gray-400 mt-1">{{ auth()->user()->phone }}</p>
                @endif
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-3 border-2 border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:border-red-300 hover:text-red-600 transition-colors">Đăng xuất</button>
            </form>
        </aside>

        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-card p-6 md:p-8">
                <h2 class="font-display font-bold text-xl mb-6">Lịch sử đơn hàng</h2>

                @if($orders->isEmpty())
                    <div class="text-center py-16">
                        <p class="text-gray-400 mb-4">Bạn chưa có đơn hàng nào</p>
                        <a href="{{ route('shop') }}" class="inline-block px-6 py-3 bg-brand-black text-white font-bold rounded-2xl hover:bg-accent transition-colors">Mua sắm ngay</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <div class="border border-gray-100 rounded-2xl p-5 hover:border-accent/20 hover:shadow-sm transition-all">
                                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                                    <div>
                                        <p class="font-display font-bold text-accent">{{ $order->order_code }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d/m/Y · H:i') }}</p>
                                    </div>
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'confirmed' => 'bg-blue-100 text-blue-700',
                                            'shipping' => 'bg-indigo-100 text-indigo-700',
                                            'delivered' => 'bg-emerald-100 text-emerald-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $order->status_label }}
                                    </span>
                                </div>
                                @foreach($order->items as $item)
                                    <div class="flex justify-between text-sm py-1">
                                        <span class="text-gray-600">{{ $item->name }} × {{ $item->quantity }}</span>
                                        <span class="font-medium">{{ number_format($item->subtotal, 0, ',', '.') }}₫</span>
                                    </div>
                                @endforeach
                                <div class="mt-4 pt-4 border-t flex justify-between font-display font-bold">
                                    <span>Tổng</span>
                                    <span class="text-accent">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
