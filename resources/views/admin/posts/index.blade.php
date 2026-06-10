@extends('layouts.admin')
@section('page_title', 'Quản lý bài viết')

@section('content')
<div class="flex justify-end mb-6"><a href="{{ route('admin.posts.create') }}" class="px-4 py-2 bg-sport-accent text-white rounded-lg text-sm">+ Thêm bài viết</a></div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Tiêu đề</th><th class="px-4 py-3 text-left">Trạng thái</th><th class="px-4 py-3 text-left">Ngày</th><th class="px-4 py-3 text-right">Thao tác</th></tr></thead>
        <tbody>
            @foreach($posts as $post)
                <tr class="border-t">
                    <td class="px-4 py-3 font-medium">{{ $post->title }}</td>
                    <td class="px-4 py-3">{{ $post->is_published ? '✅ Published' : '📝 Draft' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $post->published_at?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-gray-400">Xem</a>
                        <a href="{{ route('admin.posts.edit', $post) }}" class="text-blue-600">Sửa</a>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Xóa?')">@csrf @method('DELETE')<button class="text-red-600">Xóa</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $posts->links() }}</div>
@endsection
