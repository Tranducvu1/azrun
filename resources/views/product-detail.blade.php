@extends('layouts.app')
@section('title', $product->name . ' — SportShop')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 md:py-10">

    <nav class="text-sm text-gray-500 mb-6 flex flex-wrap items-center gap-1">
        <a href="/" class="hover:text-accent transition-colors">Trang chủ</a>
        <span>/</span>
        <a href="{{ route('shop') }}" class="hover:text-accent transition-colors">Cửa hàng</a>
        @if($product->category)
            <span>/</span>
            <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="hover:text-accent transition-colors">{{ $product->category->name }}</a>
        @endif
        <span>/</span>
        <span class="text-brand-black font-medium truncate max-w-[200px]">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14">

        {{-- Gallery --}}
        <div x-data="{ activeImg: '{{ $product->thumbnail }}' }">
            <div class="bg-white rounded-3xl overflow-hidden shadow-card mb-4 relative group">
                @if($product->isOnSale() && $product->discount_percent)
                    <span class="absolute top-4 left-4 z-10 px-3 py-1.5 bg-accent text-white text-sm font-black rounded-full shadow-lg">-{{ $product->discount_percent }}%</span>
                @endif
                <img :src="activeImg" src="{{ $product->thumbnail }}" alt="{{ $product->name }}" class="w-full aspect-square object-cover">
            </div>
            @if($product->images && count($product->images))
                <div class="grid grid-cols-5 gap-2">
                    <button @click="activeImg='{{ $product->thumbnail }}'" :class="activeImg==='{{ $product->thumbnail }}' ? 'ring-2 ring-accent ring-offset-2' : 'opacity-70 hover:opacity-100'" class="rounded-xl overflow-hidden transition-all">
                        <img src="{{ $product->thumbnail }}" alt="" class="w-full aspect-square object-cover">
                    </button>
                    @foreach($product->images as $img)
                        <button @click="activeImg='{{ $img }}'" :class="activeImg==='{{ $img }}' ? 'ring-2 ring-accent ring-offset-2' : 'opacity-70 hover:opacity-100'" class="rounded-xl overflow-hidden transition-all">
                            <img src="{{ $img }}" alt="" class="w-full aspect-square object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="lg:sticky lg:top-24 lg:self-start">
            @if($product->brand)
                <a href="{{ route('shop', ['brand' => $product->brand->slug]) }}" class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-accent hover:underline mb-3">
                    {{ $product->brand->name }}
                </a>
            @endif

            <h1 class="font-display text-2xl md:text-3xl lg:text-4xl font-bold text-brand-black leading-tight tracking-tight">{{ $product->name }}</h1>

            {{-- Rating --}}
            @php $avgRating = round($product->avg_rating, 1); $reviewCount = $product->reviews->count(); @endphp
            @if($reviewCount > 0)
                <div class="flex items-center gap-3 mt-4">
                    <div class="flex text-amber-400">
                        @for($i = 1; $i <= 5; $i++) <span>{{ $i <= round($avgRating) ? '★' : '☆' }}</span> @endfor
                    </div>
                    <span class="text-sm text-gray-500">{{ $avgRating }} · {{ $reviewCount }} đánh giá</span>
                    @if($product->sold_count > 0)
                        <span class="text-sm text-gray-400">· Đã bán {{ number_format($product->sold_count) }}</span>
                    @endif
                </div>
            @endif

            <div class="mt-6 flex items-baseline gap-3 flex-wrap">
                <span class="font-display text-4xl font-bold text-accent">{{ number_format($product->current_price, 0, ',', '.') }}₫</span>
                @if($product->isOnSale() && $product->sale_price)
                    <span class="text-xl text-gray-400 line-through">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    <span class="px-2.5 py-1 bg-lime text-brand-black text-xs font-black rounded-full">Tiết kiệm {{ number_format($product->price - $product->sale_price, 0, ',', '.') }}₫</span>
                @endif
            </div>

            <p class="text-gray-600 mt-4 leading-relaxed">{{ $product->short_description }}</p>

            {{-- Urgency --}}
            @if($product->in_stock && $product->stock_quantity <= 10)
                <div class="mt-4 flex items-center gap-2 px-4 py-3 bg-amber-50 border border-amber-200 rounded-2xl">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-500 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span></span>
                    <span class="text-sm font-semibold text-amber-800">Chỉ còn {{ $product->stock_quantity }} sản phẩm — đặt ngay!</span>
                </div>
            @endif

            @if($product->variants->count())
                <div class="mt-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-brand-black mb-3">Kích cỡ</h3>
                    <div class="flex flex-wrap gap-2" id="variant-selector">
                        @foreach($product->variants as $variant)
                            <button data-variant-id="{{ $variant->id }}"
                                    onclick="selectVariant(this)"
                                    class="min-w-[52px] px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-bold hover:border-accent transition-all {{ $variant->stock_quantity <= 0 ? 'opacity-40 cursor-not-allowed line-through' : 'cursor-pointer' }}"
                                    {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}>
                                {{ $variant->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <div class="flex items-center bg-brand-surface rounded-2xl p-1 shrink-0">
                    <button onclick="changeQty(-1)" class="w-12 h-12 flex items-center justify-center text-xl font-bold text-gray-600 hover:text-accent rounded-xl hover:bg-white transition-colors">−</button>
                    <input type="number" id="qty-input" value="1" min="1" max="99" class="w-14 text-center bg-transparent font-bold text-lg focus:outline-none">
                    <button onclick="changeQty(1)" class="w-12 h-12 flex items-center justify-center text-xl font-bold text-gray-600 hover:text-accent rounded-xl hover:bg-white transition-colors">+</button>
                </div>
                <form action="{{ route('cart.add') }}" method="POST" class="flex-1 flex gap-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="form-qty" value="1">
                    <button type="submit" class="flex-1 py-4 bg-accent text-white font-bold rounded-2xl hover:bg-accent-dark transition-all hover:shadow-glow text-sm md:text-base {{ !$product->in_stock ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$product->in_stock ? 'disabled' : '' }}>
                        🛒 Thêm vào giỏ hàng
                    </button>
                </form>
            </div>

            {{-- Trust badges --}}
            <div class="grid grid-cols-2 gap-3 mt-8">
                @foreach([
                    ['icon' => '✓', 'text' => 'Chính hãng 100%'],
                    ['icon' => '🚚', 'text' => 'Freeship đơn 1M'],
                    ['icon' => '↩', 'text' => 'Đổi trả 7 ngày'],
                    ['icon' => '⚡', 'text' => 'Giao nhanh 2H'],
                ] as $badge)
                    <div class="flex items-center gap-2 px-4 py-3 bg-brand-surface rounded-xl text-sm font-medium text-gray-700">
                        <span>{{ $badge['icon'] }}</span> {{ $badge['text'] }}
                    </div>
                @endforeach
            </div>

            @if($product->attributes)
                <div class="mt-6 border border-gray-100 rounded-2xl overflow-hidden">
                    <h3 class="px-5 py-3 bg-brand-surface font-bold text-sm uppercase tracking-wider">Thông số</h3>
                    <div class="divide-y divide-gray-100">
                        @foreach($product->attributes as $key => $value)
                            <div class="flex justify-between px-5 py-3 text-sm">
                                <span class="text-gray-500 capitalize">{{ $key }}</span>
                                <span class="font-semibold text-brand-black">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mt-16 md:mt-20" x-data="{ tab: 'description' }">
        <div class="flex gap-1 p-1 bg-brand-surface rounded-2xl w-fit">
            <button @click="tab = 'description'" :class="tab === 'description' ? 'bg-white shadow-sm text-brand-black' : 'text-gray-500'" class="px-6 py-3 text-sm font-bold rounded-xl transition-all">Mô tả sản phẩm</button>
            <button @click="tab = 'reviews'" :class="tab === 'reviews' ? 'bg-white shadow-sm text-brand-black' : 'text-gray-500'" class="px-6 py-3 text-sm font-bold rounded-xl transition-all">Đánh giá ({{ $product->reviews->count() }})</button>
        </div>
        <div class="mt-8 bg-white rounded-3xl shadow-card p-6 md:p-10">
            <div x-show="tab === 'description'" class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                {!! $product->description !!}
            </div>
            <div x-show="tab === 'reviews'" style="display:none">
                @auth
                    <form action="{{ route('reviews.store', $product) }}" method="POST" class="bg-brand-surface rounded-2xl p-6 mb-8">
                        @csrf
                        <h3 class="font-display font-bold text-brand-black mb-4">Viết đánh giá</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Số sao</label>
                                <select name="rating" class="w-full px-4 py-3 bg-white border-0 rounded-xl text-sm focus:ring-2 focus:ring-accent/20">
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} sao — {{ ['', 'Tệ', 'Tạm', 'OK', 'Tốt', 'Tuyệt vời'][$i] }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Tiêu đề</label>
                                <input type="text" name="title" class="w-full px-4 py-3 bg-white border-0 rounded-xl text-sm focus:ring-2 focus:ring-accent/20" placeholder="Tóm tắt ngắn">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nội dung</label>
                                <textarea name="content" rows="3" class="w-full px-4 py-3 bg-white border-0 rounded-xl text-sm focus:ring-2 focus:ring-accent/20" placeholder="Chia sẻ trải nghiệm chạy bộ của bạn..."></textarea>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 px-6 py-3 bg-brand-black text-white font-bold rounded-xl hover:bg-accent transition-colors">Gửi đánh giá</button>
                    </form>
                @else
                    <div class="text-center py-8 bg-brand-surface rounded-2xl mb-8">
                        <p class="text-gray-600"><a href="{{ route('login') }}" class="text-accent font-bold hover:underline">Đăng nhập</a> để viết đánh giá và nhận ưu đãi</p>
                    </div>
                @endauth

                @forelse($product->reviews as $review)
                    <div class="py-6 border-b border-gray-100 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-black text-lime flex items-center justify-center font-bold text-sm">{{ strtoupper(substr($review->user->name ?? 'K', 0, 1)) }}</div>
                            <div>
                                <p class="font-semibold text-brand-black">{{ $review->user->name ?? 'Khách hàng' }}</p>
                                <div class="flex text-amber-400 text-xs mt-0.5">
                                    @for($i = 1; $i <= 5; $i++) <span>{{ $i <= $review->rating ? '★' : '☆' }}</span> @endfor
                                </div>
                            </div>
                        </div>
                        @if($review->title) <p class="font-bold text-sm mt-3 text-brand-black">{{ $review->title }}</p> @endif
                        <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $review->content }}</p>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-8">Chưa có đánh giá — hãy là người đầu tiên!</p>
                @endforelse
            </div>
        </div>
    </div>

    @if($relatedProducts->count())
        <section class="mt-16 md:mt-20">
            @include('components.section-header', ['title' => 'Có thể bạn thích', 'subtitle' => 'Related'])
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                @foreach($relatedProducts as $relatedProduct)
                    @include('components.product-card', ['product' => $relatedProduct])
                @endforeach
            </div>
        </section>
    @endif
</div>

<script>
function changeQty(delta) {
    const input = document.getElementById('qty-input');
    const formInput = document.getElementById('form-qty');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
    formInput.value = val;
}
function selectVariant(btn) {
    document.querySelectorAll('#variant-selector button').forEach(b => b.classList.remove('border-accent', 'bg-accent/5'));
    btn.classList.add('border-accent', 'bg-accent/5');
}
</script>
@endsection
