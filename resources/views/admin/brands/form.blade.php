@extends('layouts.admin')
@section('page_title', $brand->exists ? 'Sửa thương hiệu' : 'Thêm thương hiệu')

@section('content')
<form method="POST" action="{{ $brand->exists ? route('admin.brands.update', $brand) : route('admin.brands.store') }}" class="max-w-2xl">
    @csrf @if($brand->exists) @method('PUT') @endif
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div><label class="block text-sm font-medium mb-1">Tên *</label><input type="text" name="name" value="{{ old('name', $brand->name) }}" required class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Slug</label><input type="text" name="slug" value="{{ old('slug', $brand->slug) }}" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Logo (URL)</label><input type="url" name="logo" value="{{ old('logo', $brand->logo) }}" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Mô tả</label><textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg text-sm">{{ old('description', $brand->description) }}</textarea></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $brand->is_active ?? true) ? 'checked' : '' }}> Hiển thị</label>
    </div>
    <div class="mt-6 flex gap-3">
        <button type="submit" class="px-6 py-2 bg-sport-accent text-white rounded-lg">Lưu</button>
        <a href="{{ route('admin.brands.index') }}" class="px-6 py-2 border rounded-lg text-gray-600">Huỷ</a>
    </div>
</form>
@endsection
