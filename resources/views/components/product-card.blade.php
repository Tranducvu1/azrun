@props(['product', 'variant' => 'default'])

@php
    $isDark = $variant === 'dark';
    $rating = round($product->avg_rating ?? 0, 1);
    $reviewCount = $product->reviews_count ?? $product->reviews->count() ?? 0;
@endphp

<div class="group relative {{ $isDark ? 'bg-white/5 backdrop-blur-sm border-white/10' : 'bg-white border-gray-100' }} rounded-2xl overflow-hidden border hover:border-accent/30 transition-all duration-500 hover:-translate-y-1 hover:shadow-xl hover:shadow-accent/10 card-shine">
    <div class="relative aspect-square bg-brand-surface overflow-hidden">
        <a href="{{ route('product.show', $product->slug) }}">
            <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" loading="lazy">
        </a>

        <div class="absolute top-3 left-3 flex flex-col gap-1.5 pointer-events-none">
            @if($product->isOnSale() && $product->discount_percent)
                <span class="inline-flex items-center px-2.5 py-1 bg-accent text-white text-[11px] font-bold rounded-full shadow-lg shadow-accent/30">-{{ $product->discount_percent }}%</span>
            @endif
            @if($product->created_at && $product->created_at->gt(now()->subDays(14)))
                <span class="inline-flex items-center px-2.5 py-1 bg-brand-black text-lime text-[11px] font-bold rounded-full">NEW</span>
            @endif
        </div>
        @if($product->is_featured)
            <span class="absolute top-3 right-3 px-2.5 py-1 bg-lime text-brand-black text-[11px] font-black rounded-full pointer-events-none">🔥 HOT</span>
        @endif

        @if(!$product->in_stock)
            <div class="absolute inset-0 bg-brand-black/60 backdrop-blur-[2px] flex items-center justify-center pointer-events-none">
                <span class="px-4 py-2 bg-white/10 backdrop-blur text-white text-xs font-bold rounded-full border border-white/20">HẾT HÀNG</span>
            </div>
        @elseif($product->in_stock)
            <div class="absolute inset-x-0 bottom-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-10">
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full py-2.5 bg-brand-black text-white text-xs font-bold rounded-xl hover:bg-accent transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        THÊM VÀO GIỎ
                    </button>
                </form>
            </div>
        @endif
    </div>

    <a href="{{ route('product.show', $product->slug) }}" class="block p-4">
        @if($product->brand)
            <p class="text-[10px] font-bold tracking-widest uppercase {{ $isDark ? 'text-white/50' : 'text-gray-400' }} mb-1">{{ $product->brand->name }}</p>
        @endif
        <h3 class="text-sm font-semibold {{ $isDark ? 'text-white' : 'text-brand-black' }} line-clamp-2 leading-snug min-h-[2.5rem] group-hover:text-accent transition-colors">{{ $product->name }}</h3>

        @if($rating > 0)
            <div class="flex items-center gap-1.5 mt-2">
                <div class="flex text-amber-400 text-xs">
                    @for($i = 1; $i <= 5; $i++) <span>{{ $i <= round($rating) ? '★' : '☆' }}</span> @endfor
                </div>
                <span class="text-[10px] {{ $isDark ? 'text-white/40' : 'text-gray-400' }}">({{ $reviewCount }})</span>
            </div>
        @endif

        <div class="mt-2.5 flex items-baseline gap-2 flex-wrap">
            <span class="text-lg font-bold {{ $isDark ? 'text-lime' : 'text-accent' }}">{{ number_format($product->current_price, 0, ',', '.') }}₫</span>
            @if($product->isOnSale() && $product->sale_price)
                <span class="text-xs {{ $isDark ? 'text-white/40' : 'text-gray-400' }} line-through">{{ number_format($product->price, 0, ',', '.') }}₫</span>
            @endif
        </div>

        @if($product->sold_count > 0)
            <p class="text-[10px] {{ $isDark ? 'text-white/40' : 'text-gray-400' }} mt-1.5">Đã bán {{ number_format($product->sold_count) }}</p>
        @endif
    </a>
</div>
