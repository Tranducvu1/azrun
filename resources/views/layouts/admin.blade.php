<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - ' . config('branding.name'))</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(config('branding.favicon_png')) }}">
    <link rel="icon" type="image/jpeg" href="{{ asset(config('branding.favicon')) }}">
    <link rel="apple-touch-icon" href="{{ asset(config('branding.apple_touch_icon')) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sport: {
                            dark: '#1c1917',
                            accent: '#f97316',
                            'accent-dark': '#ea580c',
                            'accent-light': '#fb923c',
                            surface: '#fff7ed',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-orange-50/40 min-h-screen">
    <div class="flex">
        <aside class="w-64 bg-gradient-to-b from-orange-600 to-orange-700 text-white min-h-screen fixed left-0 top-0 border-r border-orange-400/30 shadow-xl">
            <div class="p-5 border-b border-white/20 bg-orange-500/30">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-lg font-black tracking-tight">
                    <img src="{{ config('branding.logo') }}" alt="{{ config('branding.name') }}" class="h-8 w-8 rounded-lg object-cover ring-2 ring-white/30">
                    AZ<span class="text-lime">Run</span>
                </a>
                <p class="text-xs text-orange-100 mt-1">Quản trị cửa hàng</p>
            </div>
            <nav class="p-4 space-y-1">
                @php
                    $links = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '📊'],
                        ['route' => 'admin.categories.index', 'label' => 'Danh mục', 'icon' => '📁'],
                        ['route' => 'admin.products.index', 'label' => 'Sản phẩm', 'icon' => '👟'],
                        ['route' => 'admin.brands.index', 'label' => 'Thương hiệu', 'icon' => '🏷️'],
                        ['route' => 'admin.orders.index', 'label' => 'Đơn hàng', 'icon' => '📦'],
                        ['route' => 'admin.reviews.index', 'label' => 'Đánh giá', 'icon' => '⭐'],
                        ['route' => 'admin.banners.index', 'label' => 'Banner', 'icon' => '🖼️'],
                        ['route' => 'admin.posts.index', 'label' => 'Bài viết', 'icon' => '📝'],
                        ['route' => 'admin.coupons.index', 'label' => 'Mã giảm giá', 'icon' => '🎫'],
                    ];
                @endphp
                @foreach($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs(str_replace('.index', '.*', $link['route'])) || request()->routeIs($link['route']) ? 'bg-white text-orange-600 shadow-lg font-semibold' : 'text-orange-50 hover:bg-white/15 hover:text-white' }}">
                        <span>{{ $link['icon'] }}</span> {{ $link['label'] }}
                    </a>
                @endforeach
                <hr class="border-white/20 my-3">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-orange-50 hover:bg-white/15 hover:text-white">
                    🌐 Xem website
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-orange-50 hover:bg-white/15 hover:text-white text-left">
                        🚪 Đăng xuất
                    </button>
                </form>
            </nav>
        </aside>

        <div class="flex-1 ml-64">
            <header class="bg-gradient-to-r from-orange-500 to-orange-600 shadow-md px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                <h1 class="text-lg font-bold text-white">@yield('page_title', 'Dashboard')</h1>
                <span class="text-sm text-orange-100">{{ auth()->user()->name }}</span>
            </header>

            <main class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-orange-50 border border-orange-200 text-orange-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        @foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
