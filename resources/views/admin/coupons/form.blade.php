@extends('layouts.admin')
@section('page_title', $coupon->exists ? 'Sửa mã giảm giá' : 'Thêm mã giảm giá')

@section('content')
<form method="POST" action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" class="max-w-2xl">
    @csrf @if($coupon->exists) @method('PUT') @endif
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div><label class="block text-sm font-medium mb-1">Mã *</label><input type="text" name="code" value="{{ old('code', $coupon->code) }}" required class="w-full px-3 py-2 border rounded-lg text-sm uppercase"></div>
        <div><label class="block text-sm font-medium mb-1">Loại *</label>
            <select name="type" class="w-full px-3 py-2 border rounded-lg text-sm">
                <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Cố định (đ)</option>
            </select>
        </div>
        <div><label class="block text-sm font-medium mb-1">Giá trị *</label><input type="number" name="value" value="{{ old('value', $coupon->value) }}" required min="0" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Đơn tối thiểu</label><input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" min="0" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Giới hạn sử dụng</label><input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}> Kích hoạt</label>
    </div>
    <div class="mt-6 flex gap-3"><button type="submit" class="px-6 py-2 bg-sport-accent text-white rounded-lg">Lưu</button><a href="{{ route('admin.coupons.index') }}" class="px-6 py-2 border rounded-lg">Huỷ</a></div>
</form>
@endsection
