@extends('layouts.app')
@section('title', 'SportShop — Giày Chạy Bộ, Trail & Outdoor Chính Hãng #1 Việt Nam')

@section('content')
@php
    $banners = \App\Models\Banner::active()->ordered()->get();
    $newProducts = \App\Models\Product::active()->with('brand')->orderBy('created_at', 'desc')->take(10)->get();
    $hotProducts = \App\Models\Product::active()->with('brand')->where('is_featured', true)->orderBy('sold_count', 'desc')->take(10)->get();
    $saleProducts = \App\Models\Product::active()->with('brand')->onSale()->take(8)->get();
    $navCats = \App\Models\Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->with('children')->take(6)->get();
    $posts = \App\Models\Post::published()->orderBy('published_at', 'desc')->take(3)->get();
    $brands = \App\Models\Brand::orderBy('name')->take(12)->get();
    $brandCount = \App\Models\Brand::count();
    $totalProducts = \App\Models\Product::active()->count();
    $totalSold = \App\Models\Product::sum('sold_count');
@endphp

{{-- HERO --}}
<section class="relative bg-brand-black overflow-hidden" x-data="{ current: 0 }" x-init="setInterval(() => current = (current + 1) % {{ max($banners->count(), 1) }}, 6000)">
    <div class="relative min-h-[520px] md:min-h-[640px] lg:min-h-[720px]">
        @forelse($banners as $i => $banner)
            <div x-show="current === {{ $i }}" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                 class="absolute inset-0" {{ $i > 0 ? 'style=display:none' : '' }}>
                <img src="{{ $banner->image }}" alt="{{ $banner->title }}" class="w-full h-full object-cover" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                <div class="absolute inset-0 hero-gradient"></div>
            </div>
        @empty
            <div class="absolute inset-0 bg-gradient-to-br from-brand-black via-brand-dark to-accent/30"></div>
        @endforelse

        {{-- Hero Content --}}
        <div class="absolute inset-0 flex items-center">
            <div class="max-w-7xl mx-auto px-4 w-full">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur border border-white/20 rounded-full mb-6">
                        <span class="w-2 h-2 bg-lime rounded-full animate-pulse"></span>
                        <span class="text-lime text-xs font-bold tracking-widest uppercase">Collection 2026</span>
                    </div>
                    <h1 class="font-display text-4xl md:text-6xl lg:text-7xl font-bold text-white leading-[1.05] tracking-tight">
                        Chạy nhanh hơn.<br>
                        <span class="text-gradient">Đi xa hơn.</span>
                    </h1>
                    <p class="text-gray-400 text-base md:text-lg mt-5 max-w-lg leading-relaxed">
                        Giày chạy bộ, trail & outdoor chính hãng từ Nike, Hoka, Adidas. Freeship toàn quốc — đơn từ 1 triệu.
                    </p>
                    <div class="flex flex-wrap gap-4 mt-8">
                        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-accent text-white font-bold rounded-2xl hover:bg-accent-light transition-all hover:shadow-glow hover:-translate-y-0.5">
                            Khám phá ngay
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="{{ route('shop', ['sort' => 'bestseller']) }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 backdrop-blur text-white font-bold rounded-2xl border border-white/20 hover:bg-white/20 transition-all">
                            🔥 Sale hot
                        </a>
                    </div>
                    {{-- Social proof --}}
                    <div class="flex items-center gap-6 mt-10 pt-8 border-t border-white/10">
                        <div>
                            <p class="font-display text-2xl md:text-3xl font-bold text-white">{{ number_format($totalSold) }}+</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Sản phẩm đã bán</p>
                        </div>
                        <div class="w-px h-10 bg-white/10"></div>
                        <div>
                            <p class="font-display text-2xl md:text-3xl font-bold text-white">{{ $brandCount }}+</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Thương hiệu</p>
                        </div>
                        <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
                        <div class="hidden sm:block">
                            <p class="font-display text-2xl md:text-3xl font-bold text-lime">4.9★</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Đánh giá TB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($banners->count() > 1)
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2">
                @foreach($banners as $i => $b)
                    <button @click="current = {{ $i }}" :class="current === {{ $i }} ? 'w-8 bg-accent' : 'w-2 bg-white/40'" class="h-2 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
        @endif
    </div>
</section>

@include('components.trust-bar')

{{-- BRAND MARQUEE --}}
@if($brands->count())
<section class="py-8 bg-white border-b border-gray-100 overflow-hidden">
    <p class="text-center text-[10px] font-bold tracking-[0.3em] uppercase text-gray-400 mb-6">Đối tác chính hãng</p>
    <div class="relative">
        <div class="flex animate-brand-scroll">
            @foreach(range(1,2) as $loop)
                @foreach($brands as $brand)
                    <a href="{{ route('shop', ['brand' => $brand->slug]) }}" class="flex items-center justify-center mx-10 shrink-0 grayscale hover:grayscale-0 opacity-60 hover:opacity-100 transition-all duration-300">
                        <img src="{{ $brand->logo ?? 'https://placehold.co/120x48/f4f4f0/333?text=' . urlencode($brand->name) }}" alt="{{ $brand->name }}" class="h-10 object-contain">
                    </a>
                @endforeach
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CATEGORIES BENTO --}}
<section class="max-w-7xl mx-auto px-4 py-16 md:py-20">
    @include('components.section-header', ['title' => 'Mua theo danh mục', 'subtitle' => 'Shop by category', 'link' => route('shop')])
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
        @foreach($navCats as $i => $cat)
            <a href="{{ route('shop', ['category' => $cat->slug]) }}"
               class="group relative overflow-hidden rounded-2xl {{ $i === 0 ? 'col-span-2 row-span-2 aspect-square lg:aspect-auto lg:min-h-[280px]' : 'aspect-square' }} bg-brand-surface hover:shadow-card transition-all duration-500">
                <img src="{{ $cat->image ?? 'https://placehold.co/400x400/141414/c8ff00?text=' . urlencode($cat->name) }}" alt="{{ $cat->name }}"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-brand-black/80 via-brand-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4 md:p-5">
                    <h3 class="font-display font-bold text-white {{ $i === 0 ? 'text-xl md:text-2xl' : 'text-sm md:text-base' }}">{{ $cat->name }}</h3>
                    <p class="text-white/60 text-xs mt-1 opacity-0 group-hover:opacity-100 transition-opacity">Xem ngay →</p>
                </div>
            </a>
        @endforeach
    </div>
</section>

{{-- FLASH SALE --}}
@if($saleProducts->count())
<section class="py-16 md:py-20 bg-brand-black relative overflow-hidden">
    <div class="absolute inset-0 opacity-30" style="background: radial-gradient(ellipse at 30% 50%, rgba(255,69,0,0.3) 0%, transparent 60%), radial-gradient(ellipse at 70% 50%, rgba(200,255,0,0.1) 0%, transparent 50%);"></div>
    <div class="max-w-7xl mx-auto px-4 relative">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-accent/20 border border-accent/30 rounded-full mb-4">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-accent"></span></span>
                    <span class="text-accent text-xs font-bold uppercase tracking-wider">Live now</span>
                </div>
                <h2 class="font-display text-3xl md:text-4xl font-bold text-white">Flash Sale</h2>
                <p class="text-gray-500 text-sm mt-1">Giá sốc — số lượng có hạn</p>
            </div>
            <div class="flex items-center gap-3" x-data="{ timer: 14400 }" x-init="setInterval(() => { if(timer > 0) timer-- }, 1000)">
                <span class="text-white/50 text-sm">Kết thúc sau</span>
                <div class="flex gap-2 font-display font-bold text-white">
                    <span class="px-3 py-2 bg-white/10 rounded-xl text-lg" x-text="String(Math.floor(timer/3600)).padStart(2,'0')">04</span>
                    <span class="text-accent self-center">:</span>
                    <span class="px-3 py-2 bg-white/10 rounded-xl text-lg" x-text="String(Math.floor((timer%3600)/60)).padStart(2,'0')">00</span>
                    <span class="text-accent self-center">:</span>
                    <span class="px-3 py-2 bg-white/10 rounded-xl text-lg" x-text="String(timer%60).padStart(2,'0')">00</span>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
            @foreach($saleProducts as $product)
                @include('components.product-card', ['product' => $product, 'variant' => 'dark'])
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('shop', ['sort' => 'price-asc']) }}" class="inline-flex items-center gap-2 px-8 py-3.5 border border-white/20 text-white font-bold rounded-2xl hover:bg-white/10 transition-colors">
                Xem tất cả deal
            </a>
        </div>
    </div>
</section>
@endif

{{-- NEW ARRIVALS --}}
<section class="max-w-7xl mx-auto px-4 py-16 md:py-20">
    @include('components.section-header', ['title' => 'Hàng mới về', 'subtitle' => 'New arrivals', 'link' => route('shop', ['sort' => 'newest'])])
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4">
        @foreach($newProducts as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
</section>

{{-- PROMO SPLIT BANNER --}}
<section class="max-w-7xl mx-auto px-4 pb-16 md:pb-20">
    <div class="grid md:grid-cols-2 gap-4">
        <a href="{{ route('shop', ['category' => $navCats->first()?->slug]) }}" class="group relative overflow-hidden rounded-3xl bg-brand-black min-h-[240px] flex items-end p-8">
            <div class="absolute inset-0 bg-gradient-to-br from-accent/40 to-brand-black"></div>
            <div class="relative z-10">
                <p class="text-lime text-xs font-bold tracking-widest uppercase mb-2">Running</p>
                <h3 class="font-display text-2xl md:text-3xl font-bold text-white">Giày chạy bộ<br>2026 Collection</h3>
                <span class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-white group-hover:text-lime transition-colors">Mua ngay →</span>
            </div>
        </a>
        <a href="{{ route('shop', ['sort' => 'bestseller']) }}" class="group relative overflow-hidden rounded-3xl bg-lime min-h-[240px] flex items-end p-8">
            <div class="absolute inset-0 bg-gradient-to-br from-lime to-emerald-300 opacity-90"></div>
            <div class="relative z-10">
                <p class="text-brand-black/60 text-xs font-bold tracking-widest uppercase mb-2">Limited</p>
                <h3 class="font-display text-2xl md:text-3xl font-bold text-brand-black">Giảm đến 50%<br>Best seller</h3>
                <span class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-brand-black group-hover:underline">Săn deal →</span>
            </div>
        </a>
    </div>
</section>

{{-- BEST SELLERS --}}
<section class="bg-brand-surface py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4">
        @include('components.section-header', ['title' => 'Bán chạy nhất', 'subtitle' => 'Best sellers', 'link' => route('shop', ['sort' => 'bestseller'])])
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4">
            @foreach($hotProducts as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>

{{-- CATEGORY SECTIONS --}}
@foreach($navCats->take(3) as $sectionCat)
<section class="max-w-7xl mx-auto px-4 py-16 md:py-20 {{ $loop->even ? 'bg-white' : '' }}">
    @php $sectionProducts = \App\Models\Product::active()->with('brand')->whereHas('category', fn($q) => $q->where('id', $sectionCat->id)->orWhere('parent_id', $sectionCat->id))->take(6)->get(); @endphp
    @if($sectionProducts->count())
        @include('components.section-header', ['title' => $sectionCat->name, 'subtitle' => 'Curated for you', 'link' => route('shop', ['category' => $sectionCat->slug])])
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
            @foreach($sectionProducts as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    @endif
</section>
@endforeach

{{-- TESTIMONIALS --}}
<section class="bg-brand-black py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4">
        @include('components.section-header', ['title' => 'Runner nói gì về chúng tôi', 'subtitle' => 'Reviews', 'dark' => true])
        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['name' => 'Minh Tuấn', 'role' => 'Marathoner · HN', 'text' => 'Giày Hoka mua ở SportShop êm chân cực, giao hàng nhanh. Nhân viên tư vấn nhiệt tình, size chuẩn 100%.', 'rating' => 5],
                ['name' => 'Lan Anh', 'role' => 'Trail runner · Đà Lạt', 'text' => 'Mình hay chạy trail, shop có đủ đồ Salomon và phụ kiện. Giá tốt hơn nhiều chỗ khác, chính hãng yên tâm.', 'rating' => 5],
                ['name' => 'Hoàng Long', 'role' => 'Coach · TP.HCM', 'text' => 'Recommend học viên mua ở đây. Đa dạng thương hiệu, chính sách đổi trả rõ ràng. Top shop running VN!', 'rating' => 5],
            ] as $review)
                <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 hover:border-accent/30 transition-colors">
                    <div class="flex text-amber-400 text-sm mb-4">{{ str_repeat('★', $review['rating']) }}</div>
                    <p class="text-gray-300 text-sm leading-relaxed">"{{ $review['text'] }}"</p>
                    <div class="flex items-center gap-3 mt-5 pt-5 border-t border-white/10">
                        <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold text-sm">{{ substr($review['name'], 0, 1) }}</div>
                        <div>
                            <p class="text-white font-semibold text-sm">{{ $review['name'] }}</p>
                            <p class="text-gray-500 text-xs">{{ $review['role'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- BLOG --}}
@if($posts->count())
<section class="max-w-7xl mx-auto px-4 py-16 md:py-20">
    @include('components.section-header', ['title' => 'Tin tức & Tips chạy bộ', 'subtitle' => 'Blog', 'link' => route('blog.index')])
    <div class="grid md:grid-cols-3 gap-6">
        @foreach($posts as $i => $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group {{ $i === 0 ? 'md:col-span-2 md:row-span-1' : '' }} relative overflow-hidden rounded-2xl bg-brand-surface">
                <div class="{{ $i === 0 ? 'grid md:grid-cols-2' : '' }}">
                    <div class="overflow-hidden {{ $i === 0 ? 'md:min-h-[280px]' : 'aspect-video' }}">
                        <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="p-5 md:p-6 flex flex-col justify-center">
                        <p class="text-accent text-[10px] font-bold tracking-widest uppercase mb-2">{{ $post->published_at->format('d M Y') }}</p>
                        <h3 class="font-display font-bold text-brand-black {{ $i === 0 ? 'text-xl md:text-2xl' : 'text-base' }} group-hover:text-accent transition-colors line-clamp-2">{{ $post->title }}</h3>
                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $post->excerpt }}</p>
                        <span class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-brand-black group-hover:text-accent transition-colors">Đọc thêm →</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

@endsection
