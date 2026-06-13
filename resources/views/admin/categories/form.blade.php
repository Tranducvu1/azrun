@extends('layouts.admin')
@section('page_title', $category->exists ? 'Sửa danh mục' : 'Thêm danh mục')

@section('content')
<form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="max-w-2xl">
    @csrf @if($category->exists) @method('PUT') @endif
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div><label class="block text-sm font-medium mb-1">Tên *</label><input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Slug</label><input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Danh mục cha</label>
            <select name="parent_id" class="w-full px-3 py-2 border rounded-lg text-sm">
                <option value="">— Không có —</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="block text-sm font-medium mb-1">Mô tả</label><textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg text-sm">{{ old('description', $category->description) }}</textarea></div>
        <div><label class="block text-sm font-medium mb-1">Ảnh (URL)</label><input type="url" name="image" value="{{ old('image', $category->image) }}" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Thứ tự</label><input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}> Hiển thị</label>
    </div>
    <div class="mt-6 flex gap-3">
        <button type="submit" class="px-6 py-2 bg-sport-accent text-white rounded-lg">Lưu</button>
        <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border rounded-lg text-gray-600">Huỷ</a>
    </div>
</form>
@endsection
