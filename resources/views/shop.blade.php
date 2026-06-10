@extends('layouts.app')
@section('title', 'Cửa Hàng — SportShop')

@section('content')
{{-- Page Hero --}}
<div class="bg-brand-black text-white py-12 md:py-16 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20" style="background: radial-gradient(circle at 80% 20%, rgba(255,69,0,0.5) 0%, transparent 50%);"></div>
    <div class="max-w-7xl mx-auto px-4 relative">
        <nav class="text-sm text-gray-500 mb-4">
            <a href="/" class="hover:text-white transition-colors">Trang chủ</a>
            <span class="mx-2">/</span>
            <span class="text-white">Cửa hàng</span>
        </nav>
        <h1 class="font-display text-3xl md:text-5xl font-bold tracking-tight">
            @if(request('category'))
                {{ $categories->firstWhere('slug', request('category'))?->name ?? 'Sản phẩm' }}
            @elseif(request('brand'))
                {{ $brands->firstWhere('slug', request('brand'))?->name ?? 'Thương hiệu' }}
            @elseif(request('q'))
                Kết quả "{{ request('q') }}"
            @else
                Tất cả sản phẩm
            @endif
        </h1>
        <p class="text-gray-400 mt-2">{{ $products->total() }} sản phẩm · Chính hãng · Freeship đơn 1M</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">

        @include('components.shop-filter-sidebar', ['categories' => $categories, 'brands' => $brands])

        {{-- Products --}}
        <div class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 p-4 bg-white rounded-2xl shadow-card">
                <div class="flex flex-wrap gap-2">
                    @if(request('category') || request('brand') || request('q'))
                        <span class="text-xs text-gray-500 self-center">Đang lọc:</span>
                        @if(request('category'))
                            <span class="px-3 py-1 bg-brand-surface text-xs font-semibold rounded-full">{{ request('category') }}</span>
                        @endif
                        @if(request('brand'))
                            <span class="px-3 py-1 bg-brand-surface text-xs font-semibold rounded-full">{{ request('brand') }}</span>
                        @endif
                    @endif
                </div>
                <form method="GET" action="{{ route('shop') }}" class="flex items-center gap-2">
                    @foreach(request()->except('sort') as $key => $val)
                        @if($val) <input type="hidden" name="{{ $key }}" value="{{ $val }}"> @endif
                    @endforeach
                    <label class="text-xs text-gray-500 shrink-0">Sắp xếp</label>
                    <select name="sort" onchange="this.form.submit()" class="px-4 py-2.5 bg-brand-surface border-0 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-accent/20 cursor-pointer">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="bestseller" {{ request('sort') === 'bestseller' ? 'selected' : '' }}>Bán chạy</option>
                        <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                        <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Giá cao → thấp</option>
                        <option value="name-asc" {{ request('sort') === 'name-asc' ? 'selected' : '' }}>A → Z</option>
                    </select>
                </form>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-24 bg-white rounded-2xl shadow-card">
                    <div class="w-20 h-20 mx-auto bg-brand-surface rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <p class="font-display text-xl font-bold text-brand-black">Không tìm thấy sản phẩm</p>
                    <p class="text-gray-500 text-sm mt-2">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                    <a href="{{ route('shop') }}" class="inline-block mt-6 px-6 py-3 bg-brand-black text-white font-bold rounded-2xl hover:bg-accent transition-colors">Xem tất cả</a>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-10 flex justify-center">{{ $products->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
