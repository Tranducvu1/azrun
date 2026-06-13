@extends('layouts.admin')
@section('title', 'Sản phẩm - Admin')
@section('page_title', 'Quản lý sản phẩm')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm sản phẩm..." class="px-3 py-2 border rounded-lg text-sm w-64">
        <button class="px-4 py-2 bg-sport-accent text-white rounded-lg text-sm hover:bg-orange-600">Tìm</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-sport-accent text-white rounded-lg text-sm font-medium hover:bg-orange-600">+ Thêm sản phẩm</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">Ảnh</th>
                <th class="px-4 py-3 text-left">Tên</th>
                <th class="px-4 py-3 text-left">Danh mục</th>
                <th class="px-4 py-3 text-left">Giá</th>
                <th class="px-4 py-3 text-left">Size</th>
                <th class="px-4 py-3 text-left">Kho</th>
                <th class="px-4 py-3 text-left">Trạng thái</th>
                <th class="px-4 py-3 text-right">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3"><img src="{{ $product->displayThumbnail() }}" class="w-12 h-12 rounded object-cover" alt="{{ $product->name }}"></td>
                    <td class="px-4 py-3">
                        <p class="font-medium line-clamp-2 max-w-xs">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400">{{ $product->sku }}</p>
                    </td>
                    <td class="px-4 py-3">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium text-sport-accent">{{ number_format($product->current_price, 0, ',', '.') }}đ</td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        @if($product->variants->count())
                            {{ $product->variants->pluck('name')->join(', ') }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $product->stock_quantity }}</td>
                    <td class="px-4 py-3">
                        @if($product->is_active)
                            <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded">Active</span>
                        @else
                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-500 rounded">Ẩn</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="text-gray-400 hover:text-blue-600">Xem</a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline">Sửa</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Xóa sản phẩm này?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
