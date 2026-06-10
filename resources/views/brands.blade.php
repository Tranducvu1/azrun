@extends('layouts.app')
@section('title', 'Thương Hiệu — SportShop')

@section('content')
<div class="bg-brand-black text-white py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-lime text-xs font-bold tracking-[0.2em] uppercase mb-3">Official partners</p>
        <h1 class="font-display text-3xl md:text-5xl font-bold">Thương hiệu chính hãng</h1>
        <p class="text-gray-500 mt-2">{{ $brands->count() }} thương hiệu · Cam kết 100% authentic</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12 md:py-16">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($brands as $brand)
            <a href="{{ route('shop', ['brand' => $brand->slug]) }}" class="group bg-white rounded-2xl shadow-card p-8 text-center hover:-translate-y-1 hover:shadow-glow transition-all duration-300 border border-transparent hover:border-accent/20">
                <div class="h-16 flex items-center justify-center mb-4 grayscale group-hover:grayscale-0 transition-all duration-300">
                    <img src="{{ $brand->logo ?? 'https://placehold.co/160x64/f4f4f0/333?text=' . urlencode($brand->name) }}" alt="{{ $brand->name }}" class="max-h-full object-contain">
                </div>
                <h2 class="font-display font-bold text-brand-black group-hover:text-accent transition-colors">{{ $brand->name }}</h2>
                @if($brand->description)
                    <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $brand->description }}</p>
                @endif
                <span class="inline-block mt-4 text-xs font-bold text-accent opacity-0 group-hover:opacity-100 transition-opacity">Xem sản phẩm →</span>
            </a>
        @endforeach
    </div>
</div>
@endsection
