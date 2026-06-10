@extends('layouts.admin')
@section('page_title', 'Quản lý mã giảm giá')

@section('content')
<div class="flex justify-end mb-6"><a href="{{ route('admin.coupons.create') }}" class="px-4 py-2 bg-sport-accent text-white rounded-lg text-sm">+ Thêm mã</a></div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Mã</th><th class="px-4 py-3 text-left">Loại</th><th class="px-4 py-3 text-left">Giá trị</th><th class="px-4 py-3 text-left">Đã dùng</th><th class="px-4 py-3 text-right">Thao tác</th></tr></thead>
        <tbody>
            @foreach($coupons as $coupon)
                <tr class="border-t">
                    <td class="px-4 py-3 font-bold text-sport-accent">{{ $coupon->code }}</td>
                    <td class="px-4 py-3">{{ $coupon->type }}</td>
                    <td class="px-4 py-3">{{ $coupon->type === 'percent' ? $coupon->value . '%' : number_format($coupon->value, 0, ',', '.') . 'đ' }}</td>
                    <td class="px-4 py-3">{{ $coupon->used_count }}/{{ $coupon->usage_limit ?? '∞' }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-blue-600">Sửa</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" onsubmit="return confirm('Xóa?')">@csrf @method('DELETE')<button class="text-red-600">Xóa</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
