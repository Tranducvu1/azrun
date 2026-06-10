@extends('layouts.admin')
@section('title', ($product->exists ? 'Sửa' : 'Thêm') . ' sản phẩm - Admin')
@section('page_title', $product->exists ? 'Sửa sản phẩm' : 'Thêm sản phẩm')

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" class="max-w-4xl">
    @csrf
    @if($product->exists) @method('PUT') @endif

    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Tên sản phẩm *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-3 py-2 border rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Tự động tạo nếu để trống">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">SKU *</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full px-3 py-2 border rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Danh mục *</label>
                <select name="category_id" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->parent_id ? '— ' : '' }}{{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Thương hiệu *</label>
                <select name="brand_id" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Giá gốc *</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" class="w-full px-3 py-2 border rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Giá sale</label>
                <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" class="w-full px-3 py-2 border rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Tồn kho *</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required min="0" class="w-full px-3 py-2 border rounded-lg text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Ảnh chính</label>
                <input type="file" name="thumbnail_file" accept="image/*" class="w-full text-sm mb-2">
                <input type="url" name="thumbnail" value="{{ old('thumbnail', $product->thumbnail) }}" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Hoặc dán URL ảnh">
                @if($product->thumbnail)
                    <img src="{{ $product->thumbnail }}" class="mt-2 w-24 h-24 object-cover rounded" alt="">
                @endif
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Ảnh phụ</label>
                <input type="file" name="gallery_files[]" accept="image/*" multiple class="w-full text-sm mb-2">
                <label class="block text-xs text-gray-500 mb-1">Hoặc URL (mỗi dòng một ảnh)</label>
                <textarea name="images_text" rows="3" class="w-full px-3 py-2 border rounded-lg text-sm">{{ old('images_text', is_array($product->images) ? implode("\n", $product->images) : '') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Mô tả ngắn</label>
                <textarea name="short_description" rows="2" class="w-full px-3 py-2 border rounded-lg text-sm">{{ old('short_description', $product->short_description) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Mô tả chi tiết (HTML)</label>
                <textarea name="description" rows="6" class="w-full px-3 py-2 border rounded-lg text-sm">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded">
                    Sản phẩm nổi bật
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} class="rounded">
                    Hiển thị
                </label>
            </div>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <button type="submit" class="px-6 py-2 bg-sport-accent text-white rounded-lg font-medium hover:bg-red-600">Lưu</button>
        <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">Huỷ</a>
    </div>
</form>
@endsection
