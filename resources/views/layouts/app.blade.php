<!DOCTYPE html>
<html lang="vi" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('branding.name') . ' — Chạy Bộ, Trail & Outdoor Chính Hãng')</title>
    <meta name="description" content="@yield('meta_description', config('branding.name') . ' — Điểm đến #1 cho runner Việt Nam. Giày chạy bộ, trail, phụ kiện chính hãng. Freeship đơn 1M.')">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(config('branding.favicon_png')) }}">
    <link rel="icon" type="image/jpeg" href="{{ asset(config('branding.favicon')) }}">
    <link rel="apple-touch-icon" href="{{ asset(config('branding.apple_touch_icon')) }}">
    <link rel="stylesheet" href="{{ asset('css/storefront.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { black: '#0a0a0a', dark: '#141414', surface: '#fff7ed', cream: '#fffbf5' },
                        primary: { DEFAULT: '#f97316', light: '#fb923c', dark: '#ea580c' },
                        accent: { DEFAULT: '#f97316', light: '#fb923c', dark: '#ea580c' },
                        lime: '#c8ff00',
                    },
                    fontFamily: {
                        sans: ['Be Vietnam Pro', 'system-ui', 'sans-serif'],
                        display: ['Syne', 'Be Vietnam Pro', 'sans-serif'],
                    },
                    boxShadow: {
                        'glow': '0 0 40px rgba(255, 69, 0, 0.15)',
                        'card': '0 4px 24px rgba(0,0,0,0.06)',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
</head>
<body class="bg-brand-cream text-brand-black antialiased" x-data="{
    mobileMenu: false,
    cartOpen: false,
    searchOpen: false,
    activeMega: null,
    megaTimer: null,
    openMega(id) { clearTimeout(this.megaTimer); this.activeMega = id; },
    scheduleCloseMega() { this.megaTimer = setTimeout(() => { this.activeMega = null; }, 160); },
    cancelCloseMega() { clearTimeout(this.megaTimer); }
}">

    {{-- PROMO TICKER --}}
    <div class="bg-gradient-to-r from-primary-dark via-primary to-primary-light text-white overflow-hidden relative z-[60]">
        <div class="flex animate-marquee whitespace-nowrap py-2">
            @foreach(range(1,2) as $i)
                <div class="flex items-center shrink-0">
                    <span class="mx-8 text-xs font-medium tracking-wide flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-lime animate-pulse"></span>
                        FLASH SALE — Giảm đến 50% · Freeship đơn từ 1M · Mã <strong class="text-lime">WELCOME10</strong> giảm 10%
                    </span>
                    <span class="mx-8 text-xs font-medium tracking-wide flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                        🏃 15.000+ runner tin tưởng · Chính hãng 100% · Đổi trả 7 ngày
                    </span>
                    <span class="mx-8 text-xs font-medium tracking-wide flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-lime animate-pulse"></span>
                        Hotline 24/7: <strong class="text-lime">0846 335 858</strong>
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- HEADER --}}
    @php
        $navCats = \App\Models\Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['children', 'products' => fn($q) => $q->active()->latest()->take(2)])
            ->get();
        $cartCount = array_sum(session('cart', []));
    @endphp

    <header class="sticky top-0 z-50" x-data="{ scrolled: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)" :class="scrolled && 'header-scrolled'">

        {{-- Row 1: Logo + Search + Actions --}}
        <div class="bg-white/95 backdrop-blur-xl border-b-2 border-primary/20">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex items-center gap-4 h-[68px]">

                    <a href="/" class="flex items-center gap-3 shrink-0 group">
                        <img src="{{ config('branding.logo') }}" alt="{{ config('branding.name') }}"
                             class="h-11 w-11 rounded-xl object-cover shadow-sm ring-2 ring-primary/20 group-hover:ring-primary/40 group-hover:scale-105 transition-all duration-300">
                        <div class="hidden sm:block">
                            <span class="font-display text-[22px] font-bold tracking-tight leading-none text-brand-black">AZ<span class="text-primary">Run</span></span>
                            <p class="text-[9px] text-gray-400 font-bold tracking-[0.25em] uppercase mt-0.5">{{ config('branding.tagline') }}</p>
                        </div>
                    </a>

                    {{-- Search --}}
                    <form action="{{ route('shop') }}" method="GET" class="hidden md:flex flex-1 max-w-2xl mx-auto">
                        <div class="relative w-full group">
                            <div class="absolute inset-0 bg-gradient-to-r from-accent/20 via-lime/10 to-accent/20 rounded-2xl blur-sm opacity-0 group-focus-within:opacity-100 transition-opacity"></div>
                            <div class="relative flex items-center bg-brand-surface rounded-2xl border border-transparent group-focus-within:border-accent/30 group-focus-within:bg-white transition-all">
                                <svg class="ml-4 w-5 h-5 text-gray-400 group-focus-within:text-accent shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" name="q" placeholder="Tìm giày Nike, Hoka, áo chạy bộ..." value="{{ request('q') }}"
                                       class="flex-1 px-3 py-3 bg-transparent text-sm focus:outline-none placeholder:text-gray-400">
                                <button type="submit" class="mr-1.5 px-4 py-2 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-dark transition-colors shrink-0">Tìm</button>
                            </div>
                        </div>
                    </form>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1 shrink-0">
                        <button @click="searchOpen = !searchOpen" class="md:hidden p-2.5 text-gray-600 hover:text-accent rounded-xl hover:bg-brand-surface transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>

                        @auth
                            <a href="{{ route('account') }}" class="hidden sm:flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-2xl hover:bg-brand-surface transition-all group">
                                <div class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center text-xs font-black group-hover:bg-primary-dark transition-colors">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                                <span class="hidden lg:block text-sm font-semibold text-gray-700 max-w-[72px] truncate">{{ auth()->user()->name }}</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:inline-flex px-3 py-2 text-sm font-semibold text-gray-600 hover:text-accent transition-colors">Đăng nhập</a>
                            <a href="{{ route('register') }}" class="hidden sm:inline-flex px-4 py-2.5 text-sm font-bold bg-primary text-white rounded-xl hover:bg-primary-dark transition-all hover:shadow-glow">Đăng ký</a>
                        @endauth

                        <button @click="cartOpen = true" class="relative flex items-center gap-2 pl-3 pr-4 py-2.5 ml-1 bg-primary/10 hover:bg-primary hover:text-white rounded-2xl transition-all group">
                            <svg class="w-5 h-5 text-gray-700 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span class="hidden sm:inline text-sm font-bold">Giỏ</span>
                            @if($cartCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 min-w-[20px] h-5 px-1 bg-accent text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white">{{ $cartCount }}</span>
                            @endif
                        </button>

                        <button @click="mobileMenu = !mobileMenu" class="xl:hidden p-2.5 text-gray-700 rounded-xl hover:bg-brand-surface ml-1">
                            <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            <svg x-show="mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Mobile search --}}
                <div x-show="searchOpen" x-transition class="md:hidden pb-4" style="display:none">
                    <form action="{{ route('shop') }}" method="GET">
                        <input type="text" name="q" placeholder="Tìm sản phẩm..." class="w-full px-4 py-3 bg-brand-surface rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-accent/20">
                    </form>
                </div>
            </div>
        </div>

        {{-- Row 2: Category nav + Mega menu --}}
        <div class="hidden xl:block bg-gradient-to-r from-primary-dark via-primary to-primary relative" @mouseleave="scheduleCloseMega()">
            <div class="max-w-7xl mx-auto px-4">
                <nav class="flex items-center h-[48px]">
                    <a href="{{ route('shop') }}" class="header-nav-link text-lime font-bold mr-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Tất cả
                    </a>
                    <div class="w-px h-5 bg-white/10 mx-1"></div>

                    @foreach($navCats as $cat)
                        <div @mouseenter="openMega({{ $cat->id }})">
                            <a href="{{ route('shop', ['category' => $cat->slug]) }}"
                               class="header-nav-link"
                               :class="activeMega === {{ $cat->id }} && 'is-active'">
                                {{ $cat->name }}
                                @if($cat->children->count())
                                    <svg class="w-3 h-3 opacity-50 transition-transform duration-200" :class="activeMega === {{ $cat->id }} && 'rotate-180 opacity-100'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                @endif
                            </a>
                        </div>
                    @endforeach

                    <div class="w-px h-5 bg-white/10 mx-2"></div>
                    <a href="{{ route('shop', ['sort' => 'bestseller']) }}" class="header-nav-link">
                        <span class="px-2 py-0.5 bg-accent text-white text-[10px] font-black rounded-md mr-1">SALE</span>
                        Hot deal
                    </a>
                    <a href="{{ route('brands.index') }}" class="header-nav-link">Thương hiệu</a>
                    <a href="{{ route('blog.index') }}" class="header-nav-link">Tin tức</a>

                    <div class="ml-auto flex items-center gap-2 text-xs text-white/40 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-lime animate-pulse"></span>
                        Freeship đơn 1M
                    </div>
                </nav>
            </div>

            {{-- MEGA MENU PANEL --}}
            <div x-show="activeMega !== null"
                 @mouseenter="cancelCloseMega()"
                 @mouseleave="scheduleCloseMega()"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute left-0 right-0 top-full bg-white border-t-2 border-accent shadow-[0_24px_64px_rgba(0,0,0,0.18)] z-50"
                 style="display:none">

                @foreach($navCats as $cat)
                    <div x-show="activeMega === {{ $cat->id }}" class="max-w-7xl mx-auto px-4 py-8">
                        <div class="grid grid-cols-12 gap-8">

                            {{-- Featured category --}}
                            <div class="col-span-3">
                                <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="group block relative rounded-2xl overflow-hidden aspect-[3/4] bg-brand-black">
                                    <img src="{{ $cat->displayImage() }}"
                                         alt="{{ $cat->name }}" class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-brand-black via-brand-black/40 to-transparent"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-5">
                                        <p class="text-lime text-[10px] font-bold tracking-widest uppercase mb-1">Collection</p>
                                        <h3 class="font-display text-2xl font-bold text-white leading-tight">{{ $cat->name }}</h3>
                                        @if($cat->description)
                                            <p class="text-white/50 text-xs mt-2 line-clamp-2">{{ $cat->description }}</p>
                                        @endif
                                        <span class="inline-flex items-center gap-1 mt-4 text-sm font-bold text-white group-hover:text-lime transition-colors">
                                            Khám phá ngay
                                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </span>
                                    </div>
                                </a>
                            </div>

                            {{-- Subcategories --}}
                            <div class="col-span-5">
                                <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-gray-400 mb-4">Danh mục con</p>
                                @if($cat->children->count())
                                    <div class="grid grid-cols-2 gap-1">
                                        @foreach($cat->children as $child)
                                            <a href="{{ route('shop', ['category' => $child->slug]) }}" class="mega-subcat-tile group">
                                                <div class="mega-subcat-img w-12 h-12 rounded-xl overflow-hidden shrink-0 ring-2 ring-gray-100">
                                                    <img src="{{ $child->displayImage() }}"
                                                         alt="{{ $child->name }}" class="w-full h-full object-cover">
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-brand-black group-hover:text-accent transition-colors">{{ $child->name }}</p>
                                                    <p class="text-[10px] text-gray-400">Xem sản phẩm →</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm mb-4">Khám phá toàn bộ sản phẩm {{ $cat->name }}</p>
                                    <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="inline-flex px-5 py-2.5 bg-brand-black text-white text-sm font-bold rounded-xl hover:bg-accent transition-colors">Xem tất cả →</a>
                                @endif
                                <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="inline-flex items-center gap-1 mt-5 text-sm font-bold text-accent hover:underline">
                                    Tất cả {{ $cat->name }} →
                                </a>
                            </div>

                            {{-- Featured products --}}
                            <div class="col-span-2">
                                <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-gray-400 mb-4">Nổi bật</p>
                                <div class="space-y-3">
                                    @forelse($cat->products as $fp)
                                        <a href="{{ route('product.show', $fp->slug) }}" class="flex gap-3 p-2 rounded-xl hover:bg-brand-surface transition-colors group">
                                            <img src="{{ $fp->displayThumbnail() }}" alt="{{ $fp->name }}" class="w-14 h-14 rounded-xl object-cover shrink-0 ring-1 ring-gray-100">
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-brand-black line-clamp-2 group-hover:text-accent transition-colors">{{ $fp->name }}</p>
                                                <p class="text-sm font-bold text-accent mt-0.5">{{ number_format($fp->current_price, 0, ',', '.') }}₫</p>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="text-xs text-gray-400">Sản phẩm sắp cập nhật</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Promo --}}
                            <div class="col-span-2">
                                <div class="rounded-2xl p-5 bg-gradient-to-br from-brand-black to-brand-dark text-white h-full flex flex-col justify-between relative overflow-hidden">
                                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-accent/30 rounded-full blur-2xl"></div>
                                    <div class="relative">
                                        <p class="text-lime text-[10px] font-black tracking-widest uppercase">Flash</p>
                                        <p class="font-display font-bold text-xl mt-2 leading-tight">Giảm đến 50%</p>
                                        <p class="text-white/50 text-xs mt-2">Mã WELCOME10 · Freeship 1M</p>
                                    </div>
                                    <a href="{{ route('shop', ['sort' => 'bestseller']) }}" class="relative mt-4 block text-center py-2.5 bg-accent text-white text-xs font-black rounded-xl hover:bg-accent-light transition-colors">
                                        Săn deal ngay
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenu" x-transition class="xl:hidden bg-gradient-to-b from-primary to-primary-dark border-t border-white/10 max-h-[80vh] overflow-y-auto">
            <div class="max-w-7xl mx-auto px-4 py-5">
                <form action="{{ route('shop') }}" method="GET" class="mb-5">
                    <input type="text" name="q" placeholder="Tìm sản phẩm..." class="w-full px-4 py-3.5 bg-white/10 text-white placeholder:text-white/40 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-accent/40 border border-white/10">
                </form>
                @foreach($navCats as $cat)
                    <div x-data="{ open: false }" class="border-b border-white/5 last:border-0">
                        <div class="flex items-center">
                            <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="flex-1 flex items-center gap-3 py-4">
                                <img src="{{ $cat->displayImage() }}" class="w-10 h-10 rounded-xl object-cover" alt="">
                                <span class="font-semibold text-white">{{ $cat->name }}</span>
                            </a>
                            @if($cat->children->count())
                                <button @click="open = !open" class="p-3 text-white/40">
                                    <svg :class="open && 'rotate-180'" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            @endif
                        </div>
                        @if($cat->children->count())
                            <div x-show="open" class="pb-3 pl-14 space-y-1" style="display:none">
                                @foreach($cat->children as $child)
                                    <a href="{{ route('shop', ['category' => $child->slug]) }}" class="block py-2 text-sm text-white/60 hover:text-lime transition-colors">{{ $child->name }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
                <div class="pt-4 grid grid-cols-2 gap-3">
                    <a href="{{ route('shop', ['sort' => 'bestseller']) }}" class="py-3 text-center font-bold text-accent bg-accent/10 rounded-2xl">🔥 Sale</a>
                    <a href="{{ route('brands.index') }}" class="py-3 text-center font-bold text-white bg-white/10 rounded-2xl">Thương hiệu</a>
                </div>
                @guest
                    <div class="pt-4 flex gap-3">
                        <a href="{{ route('login') }}" class="flex-1 py-3.5 text-center font-semibold border border-white/20 text-white rounded-2xl">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="flex-1 py-3.5 text-center font-bold bg-accent text-white rounded-2xl">Đăng ký</a>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    {{-- CART DRAWER --}}
    <div x-show="cartOpen" class="fixed inset-0 z-[100]" style="display:none">
        <div class="absolute inset-0 bg-brand-black/60 backdrop-blur-sm" @click="cartOpen = false" x-transition.opacity></div>
        <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="font-display text-lg font-bold">Giỏ Hàng <span class="text-accent">({{ $cartCount }})</span></h3>
                <button @click="cartOpen = false" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-brand-surface text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-5">
                @php $cartItems = session('cart', []); @endphp
                @if(empty($cartItems))
                    <div class="text-center py-16">
                        <div class="w-20 h-20 mx-auto bg-brand-surface rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <p class="text-gray-500 font-medium">Giỏ hàng trống</p>
                        <p class="text-sm text-gray-400 mt-1">Khám phá bộ sưu tập mới nhất!</p>
                        <a href="{{ route('shop') }}" @click="cartOpen=false" class="inline-block mt-6 px-6 py-3 bg-brand-black text-white font-bold rounded-full hover:bg-accent transition-colors">Mua sắm ngay</a>
                    </div>
                @else
                    @php $miniTotal = 0; @endphp
                    <div class="space-y-4">
                        @foreach($cartItems as $productId => $qty)
                            @php $p = \App\Models\Product::with('brand')->find($productId); @endphp
                            @if($p)
                                @php $miniTotal += $p->current_price * $qty; @endphp
                                <div class="flex gap-4 p-3 bg-brand-surface rounded-2xl">
                                    <img src="{{ $p->displayThumbnail() }}" class="w-20 h-20 rounded-xl object-cover shrink-0" alt="{{ $p->name }}">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-brand-black line-clamp-2">{{ $p->name }}</p>
                                        @if($p->brand)<p class="text-[10px] text-gray-400 uppercase tracking-wider mt-0.5">{{ $p->brand->name }}</p>@endif
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">x{{ $qty }}</span>
                                            <span class="text-sm font-bold text-accent">{{ number_format($p->current_price * $qty, 0, ',', '.') }}₫</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
            @if(!empty($cartItems))
                <div class="p-5 border-t border-gray-100 bg-brand-cream">
                    <div class="flex justify-between mb-4">
                        <span class="text-gray-600">Tạm tính</span>
                        <span class="font-bold text-lg">{{ number_format($miniTotal ?? 0, 0, ',', '.') }}₫</span>
                    </div>
                    <a href="{{ route('checkout') }}" class="block w-full py-3.5 bg-accent text-white text-center font-bold rounded-2xl hover:bg-accent-dark transition-colors shimmer">Thanh Toán Ngay</a>
                    <a href="{{ route('cart') }}" class="block w-full py-3 mt-2 text-center text-sm font-semibold text-gray-600 hover:text-accent">Xem giỏ hàng đầy đủ</a>
                </div>
            @endif
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="fixed top-24 left-1/2 -translate-x-1/2 z-[90] max-w-md w-full px-4 toast-enter">
            <div class="bg-brand-black text-white px-5 py-3.5 rounded-2xl shadow-glow flex items-center gap-3">
                <span class="w-8 h-8 bg-lime/20 rounded-full flex items-center justify-center shrink-0 text-lime">✓</span>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="fixed top-24 left-1/2 -translate-x-1/2 z-[90] max-w-md w-full px-4 toast-enter">
            <div class="bg-red-600 text-white px-5 py-3.5 rounded-2xl shadow-lg flex items-center gap-3">
                <span class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center shrink-0">!</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gradient-to-br from-primary-dark via-primary to-primary-light text-white mt-0 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background: radial-gradient(ellipse at 20% 0%, rgba(200,255,0,0.25) 0%, transparent 50%), radial-gradient(ellipse at 80% 100%, rgba(0,0,0,0.15) 0%, transparent 50%);"></div>

        {{-- Newsletter CTA --}}
        <div class="border-b border-white/20 relative">
            <div class="max-w-7xl mx-auto px-4 py-16">
                <div class="grid lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <p class="text-lime text-xs font-bold tracking-[0.2em] uppercase mb-3">Join the crew</p>
                        <h2 class="font-display text-3xl md:text-4xl font-bold text-white leading-tight">Nhận ưu đãi độc quyền<br><span class="text-lime">dành riêng cho runner</span></h2>
                        <p class="text-white/70 mt-3 text-sm">Giảm 10% đơn đầu tiên + cập nhật sản phẩm mới mỗi tuần</p>
                    </div>
                    <form class="flex flex-col sm:flex-row gap-3">
                        <input type="email" placeholder="Email của bạn" class="flex-1 px-5 py-4 bg-white/15 border border-white/25 rounded-2xl text-white placeholder-white/50 focus:outline-none focus:border-white/50 focus:ring-2 focus:ring-white/20 text-sm backdrop-blur-sm">
                        <button type="button" class="px-8 py-4 bg-brand-black text-white font-bold rounded-2xl hover:bg-brand-black/80 transition-colors shrink-0 shadow-lg">Nhận ưu đãi</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-14 relative">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <img src="{{ config('branding.logo') }}" alt="{{ config('branding.name') }}"
                             class="h-12 w-12 rounded-xl object-cover shadow-lg ring-2 ring-white/30">
                        <span class="font-display text-xl font-bold text-white">AZ<span class="text-lime">Run</span></span>
                    </div>
                    <p class="text-sm leading-relaxed text-white/75">Hệ thống bán lẻ chuyên sâu về chạy bộ, trail và outdoor — nơi runner Việt tìm thấy đam mê.</p>
                    <div class="flex gap-3 mt-5">
                        @foreach(['facebook','instagram','youtube'] as $social)
                            <a href="#" class="w-10 h-10 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center hover:bg-white/25 hover:text-lime transition-colors">
                                <span class="text-xs font-bold uppercase">{{ substr($social, 0, 2) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 class="font-display font-bold text-white mb-5 text-sm tracking-wider uppercase">Cửa hàng</h4>
                    <ul class="text-sm space-y-3 text-white/75">
                        <li><a href="{{ route('shop') }}" class="hover:text-lime transition-colors">Tất cả sản phẩm</a></li>
                        <li><a href="{{ route('shop', ['sort' => 'newest']) }}" class="hover:text-lime transition-colors">Hàng mới về</a></li>
                        <li><a href="{{ route('shop', ['sort' => 'bestseller']) }}" class="hover:text-lime transition-colors">Bán chạy nhất</a></li>
                        <li><a href="{{ route('brands.index') }}" class="hover:text-lime transition-colors">Thương hiệu</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-lime transition-colors">Blog & Tips</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-display font-bold text-white mb-5 text-sm tracking-wider uppercase">Hỗ trợ</h4>
                    <ul class="text-sm space-y-3 text-white/75">
                        <li><a href="#" class="hover:text-lime transition-colors">Chính sách đổi trả</a></li>
                        <li><a href="#" class="hover:text-lime transition-colors">Vận chuyển & giao hàng</a></li>
                        <li><a href="#" class="hover:text-lime transition-colors">Thanh toán</a></li>
                        <li><a href="#" class="hover:text-lime transition-colors">Bảo hành</a></li>
                        <li><a href="tel:0846335858" class="hover:text-lime transition-colors font-semibold text-white">📞 0846 335 858</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-display font-bold text-white mb-5 text-sm tracking-wider uppercase">Showroom</h4>
                    <div class="text-sm space-y-4 text-white/75">
                        <div>
                            <p class="text-lime font-semibold text-xs mb-1">HÀ NỘI</p>
                            <p>58A Ngõ 92, Thanh Nhàn, Bạch Mai</p>
                            <p class="mt-1">Luxury Park Views, Cầu Giấy</p>
                        </div>
                        <div>
                            <p class="text-lime font-semibold text-xs mb-1">TP. HCM</p>
                            <p>285/21 CMT8, Hòa Hưng</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-14 pt-8 border-t border-white/20 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-white/60">
                <p>© 2026 {{ config('branding.name') }} · GPKD 0109685009 · Chính hãng 100%</p>
                <div class="flex gap-4">
                    <img src="https://placehold.co/40x24/fff/ea580c?text=VISA" alt="Visa" class="h-6 opacity-70 rounded">
                    <img src="https://placehold.co/40x24/fff/ea580c?text=MC" alt="Mastercard" class="h-6 opacity-70 rounded">
                    <img src="https://placehold.co/40x24/fff/ea580c?text=VNPAY" alt="VNPay" class="h-6 opacity-70 rounded">
                </div>
            </div>
        </div>
    </footer>

    {{-- Floating hotline --}}
    <a href="tel:0846335858" class="fixed bottom-6 right-6 z-40 w-14 h-14 bg-accent text-white rounded-full shadow-glow flex items-center justify-center hover:scale-110 transition-transform animate-float" title="Gọi hotline">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
    </a>

    @stack('scripts')
</body>
</html>
