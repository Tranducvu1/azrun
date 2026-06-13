@extends('layouts.admin')
@section('page_title', 'Quản lý đơn hàng')

@section('content')
<div class="flex flex-wrap gap-3 mb-6">
    <form method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm mã đơn, SĐT..." class="px-3 py-2 border rounded-lg text-sm">
        <select name="status" class="px-3 py-2 border rounded-lg text-sm">
            <option value="">Tất cả trạng thái</option>
            @foreach(['pending','confirmed','shipping','delivered','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
        <button class="px-4 py-2 bg-sport-accent text-white rounded-lg text-sm hover:bg-orange-600">Lọc</button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Mã đơn</th><th class="px-4 py-3 text-left">Khách</th><th class="px-4 py-3 text-left">Tổng</th><th class="px-4 py-3 text-left">TT</th><th class="px-4 py-3 text-left">Ngày</th><th class="px-4 py-3 text-right">Thao tác</th></tr></thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-sport-accent">{{ $order->order_code }}</td>
                    <td class="px-4 py-3">{{ $order->name }}<br><span class="text-xs text-gray-400">{{ $order->phone }}</span></td>
                    <td class="px-4 py-3 font-bold">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 bg-gray-100 rounded">{{ $order->status_label }}</span></td>
                    <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600">Chi tiết</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
