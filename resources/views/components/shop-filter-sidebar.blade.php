{{-- Shop filter sidebar — dark premium --}}
@props(['categories', 'brands'])

<aside class="w-full lg:w-[300px] shrink-0" x-data="{ sidebarOpen: true, openCat: '{{ request('category') }}' }">
    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden w-full flex items-center justify-between px-5 py-4 bg-gradient-to-r from-primary-dark to-primary text-white rounded-2xl font-display font-bold mb-4">
        <span class="flex items-center gap-2">
            <svg class="w-5 h-5 text-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            Bộ lọc & Danh mục
        </span>
        <svg :class="sidebarOpen && 'rotate-180'" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <div x-show="sidebarOpen" class="lg:block space-y-4 sticky top-[140px]">

        {{-- Categories card --}}
        <div class="bg-gradient-to-b from-primary-dark to-primary rounded-3xl overflow-hidden shadow-glow border border-orange-400/20">
            <div class="px-5 py-4 border-b border-white/10 flex items-center justify-between">
                <div>
                    <p class="text-lime text-[10px] font-bold tracking-[0.2em] uppercase">Browse</p>
                    <h3 class="font-display font-bold text-white text-lg">Danh mục</h3>
                </div>
                <a href="{{ route('shop') }}" class="text-xs font-bold text-white/50 hover:text-lime transition-colors">Tất cả →</a>
            </div>

            <div class="p-3 max-h-[420px] overflow-y-auto sidebar-scroll">
                {{-- All products --}}
                <a href="{{ route('shop') }}"
                   class="sidebar-cat-item flex items-center gap-3 px-3 py-3 rounded-2xl mb-1 transition-all {{ !request('category') ? 'sidebar-cat-active' : 'hover:bg-white/5' }}">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-accent to-accent-light flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white">Tất cả sản phẩm</p>
                        <p class="text-[10px] text-white/40">Xem toàn bộ</p>
                    </div>
                    @if(!request('category'))
                        <span class="w-2 h-2 rounded-full bg-lime shrink-0"></span>
                    @endif
                </a>

                @foreach($categories as $cat)
                    @php
                        $isParentActive = request('category') === $cat->slug;
                        $isChildActive = $cat->children->contains('slug', request('category'));
                        $isOpen = $isParentActive || $isChildActive;
                    @endphp
                    <div class="mb-1" x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('shop', ['category' => $cat->slug]) }}"
                               class="sidebar-cat-item flex-1 flex items-center gap-3 px-3 py-3 rounded-2xl transition-all {{ $isParentActive ? 'sidebar-cat-active' : 'hover:bg-white/5' }}">
                                <div class="w-11 h-11 rounded-xl overflow-hidden shrink-0 ring-1 ring-white/10">
                                    <img src="{{ $cat->displayImage() }}"
                                         alt="{{ $cat->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-white truncate">{{ $cat->name }}</p>
                                    <p class="text-[10px] text-white/40">{{ $cat->children->count() }} danh mục con</p>
                                </div>
                            </a>
                            @if($cat->children->count())
                                <button @click="open = !open" class="w-9 h-9 flex items-center justify-center rounded-xl text-white/40 hover:text-lime hover:bg-white/5 transition-all shrink-0">
                                    <svg :class="open && 'rotate-180'" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            @endif
                        </div>

                        @if($cat->children->count())
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="ml-5 pl-3 border-l border-white/10 mt-1 space-y-0.5" style="display:none">
                                @foreach($cat->children as $child)
                                    <a href="{{ route('shop', ['category' => $child->slug]) }}"
                                       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm transition-all {{ request('category') === $child->slug ? 'bg-accent/20 text-lime font-bold' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ request('category') === $child->slug ? 'bg-lime' : 'bg-white/20' }}"></span>
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Brands --}}
        <div class="bg-white rounded-3xl shadow-card p-5 border border-gray-100">
            <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-gray-400 mb-1">Brands</p>
            <h3 class="font-display font-bold text-brand-black mb-4">Thương hiệu</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($brands->take(12) as $brand)
                    <a href="{{ route('shop', array_merge(request()->except('brand'), ['brand' => $brand->slug])) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold border transition-all {{ request('brand') === $brand->slug ? 'bg-brand-black text-white border-brand-black shadow-md' : 'bg-brand-surface text-gray-700 border-transparent hover:border-accent/30 hover:text-accent' }}">
                        @if($brand->logo)
                            <img src="{{ $brand->logo }}" alt="" class="w-4 h-4 object-contain">
                        @endif
                        {{ $brand->name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Price --}}
        <div class="bg-white rounded-3xl shadow-card p-5 border border-gray-100">
            <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-gray-400 mb-1">Budget</p>
            <h3 class="font-display font-bold text-brand-black mb-4">Khoảng giá</h3>
            <div class="grid grid-cols-2 gap-2 mb-4">
                @foreach([
                    ['min' => '', 'max' => '1000000', 'label' => 'Dưới 1M'],
                    ['min' => '1000000', 'max' => '3000000', 'label' => '1M — 3M'],
                    ['min' => '3000000', 'max' => '5000000', 'label' => '3M — 5M'],
                    ['min' => '5000000', 'max' => '', 'label' => 'Trên 5M'],
                ] as $preset)
                    <a href="{{ route('shop', array_merge(request()->except(['price_min', 'price_max']), array_filter(['price_min' => $preset['min'], 'price_max' => $preset['max']]))) }}"
                       class="px-3 py-2.5 text-center text-xs font-bold rounded-xl border transition-all {{ request('price_min') == $preset['min'] && request('price_max') == $preset['max'] ? 'bg-accent text-white border-accent' : 'bg-brand-surface text-gray-700 border-transparent hover:border-accent/30' }}">
                        {{ $preset['label'] }}
                    </a>
                @endforeach
            </div>
            <form method="GET" action="{{ route('shop') }}" class="space-y-2">
                @foreach(request()->except(['price_min', 'price_max']) as $key => $val)
                    @if($val) <input type="hidden" name="{{ $key }}" value="{{ $val }}"> @endif
                @endforeach
                <div class="flex gap-2">
                    <input type="number" name="price_min" placeholder="Từ ₫" value="{{ request('price_min') }}" class="w-full px-3 py-2.5 bg-brand-surface rounded-xl text-sm border-0 focus:ring-2 focus:ring-accent/20">
                    <input type="number" name="price_max" placeholder="Đến ₫" value="{{ request('price_max') }}" class="w-full px-3 py-2.5 bg-brand-surface rounded-xl text-sm border-0 focus:ring-2 focus:ring-accent/20">
                </div>
                <button class="w-full py-2.5 bg-white text-primary text-sm font-bold rounded-xl hover:bg-orange-50 transition-colors">Lọc giá</button>
            </form>
        </div>

        {{-- Promo mini --}}
        <div class="rounded-3xl p-5 bg-gradient-to-br from-accent via-accent-light to-lime text-brand-black relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/20 rounded-full blur-2xl"></div>
            <p class="text-xs font-black uppercase tracking-wider opacity-70">Flash deal</p>
            <p class="font-display font-bold text-lg mt-1 leading-tight">Giảm 10% đơn đầu</p>
            <p class="text-xs mt-1 opacity-70">Mã: <strong>WELCOME10</strong></p>
            <a href="{{ route('shop', ['sort' => 'bestseller']) }}" class="inline-block mt-4 px-4 py-2 bg-brand-black text-white text-xs font-bold rounded-xl hover:bg-brand-dark transition-colors">Săn deal →</a>
        </div>
    </div>
</aside>
