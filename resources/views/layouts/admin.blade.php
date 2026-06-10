<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - SportShop')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sport: { dark:'#1a1a2e', accent:'#e94560' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-sport-dark text-white min-h-screen fixed left-0 top-0">
            <div class="p-5 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-black">SPORT<span class="text-sport-accent">SHOP</span></a>
                <p class="text-xs text-gray-400 mt-1">Admin Panel</p>
            </div>
            <nav class="p-4 space-y-1">
                @php
                    $links = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '📊'],
                        ['route' => 'admin.products.index', 'label' => 'Sản phẩm', 'icon' => '👟'],
                        ['route' => 'admin.categories.index', 'label' => 'Danh mục', 'icon' => '📁'],
                        ['route' => 'admin.brands.index', 'label' => 'Thương hiệu', 'icon' => '🏷️'],
                        ['route' => 'admin.orders.index', 'label' => 'Đơn hàng', 'icon' => '📦'],
                        ['route' => 'admin.banners.index', 'label' => 'Banner', 'icon' => '🖼️'],
                        ['route' => 'admin.posts.index', 'label' => 'Bài viết', 'icon' => '📝'],
                        ['route' => 'admin.coupons.index', 'label' => 'Mã giảm giá', 'icon' => '🎫'],
                    ];
                @endphp
                @foreach($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs(str_replace('.index', '.*', $link['route'])) || request()->routeIs($link['route']) ? 'bg-sport-accent text-white' : 'text-gray-300 hover:bg-white/10' }}">
                        <span>{{ $link['icon'] }}</span> {{ $link['label'] }}
                    </a>
                @endforeach
                <hr class="border-white/10 my-3">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-white/10">
                    🌐 Xem website
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-white/10 text-left">
                        🚪 Đăng xuất
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main -->
        <div class="flex-1 ml-64">
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                <h1 class="text-lg font-bold text-gray-800">@yield('page_title', 'Dashboard')</h1>
                <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
            </header>

            <main class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
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
