@extends('layouts.admin')
@section('page_title', 'Quản lý danh mục')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-sport-accent text-white rounded-lg text-sm font-medium">+ Thêm danh mục</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Tên</th><th class="px-4 py-3 text-left">Slug</th><th class="px-4 py-3 text-left">SP</th><th class="px-4 py-3 text-left">Trạng thái</th><th class="px-4 py-3 text-right">Thao tác</th></tr></thead>
        <tbody>
            @foreach($categories as $category)
                <tr class="border-t">
                    <td class="px-4 py-3 font-medium">{{ $category->parent ? '— ' : '' }}{{ $category->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $category->slug }}</td>
                    <td class="px-4 py-3">{{ $category->products_count }}</td>
                    <td class="px-4 py-3">{{ $category->is_active ? '✅' : '❌' }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Xóa?')">@csrf @method('DELETE')<button class="text-red-600">Xóa</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $categories->links() }}</div>
@endsection
