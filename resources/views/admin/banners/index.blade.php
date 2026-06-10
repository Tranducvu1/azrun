@extends('layouts.admin')
@section('page_title', 'Quản lý banner')

@section('content')
<div class="flex justify-end mb-6"><a href="{{ route('admin.banners.create') }}" class="px-4 py-2 bg-sport-accent text-white rounded-lg text-sm">+ Thêm banner</a></div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach($banners as $banner)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <img src="{{ $banner->image }}" class="w-full h-40 object-cover" alt="">
            <div class="p-4 flex items-center justify-between">
                <div><p class="font-medium">{{ $banner->title }}</p><p class="text-xs text-gray-400">{{ $banner->is_active ? 'Active' : 'Ẩn' }}</p></div>
                <div class="space-x-2 text-sm">
                    <a href="{{ route('admin.banners.edit', $banner) }}" class="text-blue-600">Sửa</a>
                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Xóa?')">@csrf @method('DELETE')<button class="text-red-600">Xóa</button></form>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="mt-4">{{ $banners->links() }}</div>
@endsection
