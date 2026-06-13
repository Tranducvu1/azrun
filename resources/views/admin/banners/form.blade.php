@extends('layouts.admin')
@section('page_title', $banner->exists ? 'Sửa banner' : 'Thêm banner')

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" class="max-w-2xl">
    @csrf @if($banner->exists) @method('PUT') @endif
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div><label class="block text-sm font-medium mb-1">Tiêu đề *</label><input type="text" name="title" value="{{ old('title', $banner->title) }}" required class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div>
            <label class="block text-sm font-medium mb-1">Ảnh banner *</label>
            <input type="file" name="image_file" accept="image/*" class="w-full text-sm mb-2">
            <input type="url" name="image" value="{{ old('image', $banner->image) }}" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Hoặc dán URL ảnh">
            @if($banner->image)<img src="{{ $banner->displayImage() }}" class="mt-2 h-20 rounded object-cover" alt="">@endif
        </div>
        <div><label class="block text-sm font-medium mb-1">Link</label><input type="text" name="link" value="{{ old('link', $banner->link) }}" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Thứ tự</label><input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}> Hiển thị</label>
    </div>
    <div class="mt-6 flex gap-3"><button type="submit" class="px-6 py-2 bg-sport-accent text-white rounded-lg">Lưu</button><a href="{{ route('admin.banners.index') }}" class="px-6 py-2 border rounded-lg">Huỷ</a></div>
</form>
@endsection
