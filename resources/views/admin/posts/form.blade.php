@extends('layouts.admin')
@section('page_title', $post->exists ? 'Sửa bài viết' : 'Thêm bài viết')

@section('content')
<form method="POST" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" class="max-w-4xl">
    @csrf @if($post->exists) @method('PUT') @endif
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div><label class="block text-sm font-medium mb-1">Tiêu đề *</label><input type="text" name="title" value="{{ old('title', $post->title) }}" required class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Slug</label><input type="text" name="slug" value="{{ old('slug', $post->slug) }}" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Tóm tắt</label><textarea name="excerpt" rows="2" class="w-full px-3 py-2 border rounded-lg text-sm">{{ old('excerpt', $post->excerpt) }}</textarea></div>
        <div><label class="block text-sm font-medium mb-1">Nội dung (HTML) *</label><textarea name="content" rows="10" required class="w-full px-3 py-2 border rounded-lg text-sm">{{ old('content', $post->content) }}</textarea></div>
        <div><label class="block text-sm font-medium mb-1">Ảnh thumbnail (URL)</label><input type="url" name="thumbnail" value="{{ old('thumbnail', $post->thumbnail) }}" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }}> Xuất bản</label>
    </div>
    <div class="mt-6 flex gap-3"><button type="submit" class="px-6 py-2 bg-sport-accent text-white rounded-lg">Lưu</button><a href="{{ route('admin.posts.index') }}" class="px-6 py-2 border rounded-lg">Huỷ</a></div>
</form>
@endsection
