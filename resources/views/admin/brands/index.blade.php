@extends('layouts.admin')
@section('page_title', 'Quản lý thương hiệu')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.brands.create') }}" class="px-4 py-2 bg-sport-accent text-white rounded-lg text-sm font-medium">+ Thêm thương hiệu</a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Logo</th><th class="px-4 py-3 text-left">Tên</th><th class="px-4 py-3 text-left">SP</th><th class="px-4 py-3 text-right">Thao tác</th></tr></thead>
        <tbody>
            @foreach($brands as $brand)
                <tr class="border-t">
                    <td class="px-4 py-3"><img src="{{ $brand->logo }}" class="h-8 object-contain" alt=""></td>
                    <td class="px-4 py-3 font-medium">{{ $brand->name }}</td>
                    <td class="px-4 py-3">{{ $brand->products_count }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.brands.edit', $brand) }}" class="text-blue-600">Sửa</a>
                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="inline" onsubmit="return confirm('Xóa?')">@csrf @method('DELETE')<button class="text-red-600">Xóa</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $brands->links() }}</div>
@endsection
