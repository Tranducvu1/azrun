@extends('layouts.admin')
@section('title', 'Đánh giá - Admin')
@section('page_title', 'Duyệt đánh giá')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
        <a href="{{ route('admin.reviews.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-sport-accent text-white' : 'bg-white border text-gray-600' }}">
            Tất cả
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'pending' ? 'bg-sport-accent text-white' : 'bg-white border text-gray-600' }}">
            Chờ duyệt @if($pendingCount > 0)<span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white text-xs rounded-full">{{ $pendingCount }}</span>@endif
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'approved' ? 'bg-sport-accent text-white' : 'bg-white border text-gray-600' }}">
            Đã duyệt
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">Sản phẩm</th>
                <th class="px-4 py-3 text-left">Khách</th>
                <th class="px-4 py-3 text-left">Sao</th>
                <th class="px-4 py-3 text-left">Nội dung</th>
                <th class="px-4 py-3 text-left">Trạng thái</th>
                <th class="px-4 py-3 text-right">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" class="font-medium text-sport-accent hover:underline">
                            {{ $review->product->name }}
                        </a>
                    </td>
                    <td class="px-4 py-3">{{ $review->user->name ?? 'Khách' }}</td>
                    <td class="px-4 py-3 text-amber-400">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                    <td class="px-4 py-3 max-w-xs">
                        @if($review->title)<p class="font-semibold">{{ $review->title }}</p>@endif
                        <p class="text-gray-500 line-clamp-2">{{ $review->content }}</p>
                    </td>
                    <td class="px-4 py-3">
                        @if($review->is_approved)
                            <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded">Đã duyệt</span>
                        @else
                            <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-700 rounded">Chờ duyệt</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        @if(!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-green-600 hover:underline">Duyệt</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline" onsubmit="return confirm('Xóa đánh giá này?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Chưa có đánh giá</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $reviews->links() }}</div>
@endsection
