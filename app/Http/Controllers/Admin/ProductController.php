<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(private ImageUploadService $uploader) {}

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($search = $request->get('q')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.products.form', ['product' => new Product(), 'categories' => $categories, 'brands' => $brands]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.products.form', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validated($request, $product));
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm!');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . ($product?->id ?? 'NULL'),
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . ($product?->id ?? 'NULL'),
            'stock_quantity' => 'required|integer|min:0',
            'thumbnail' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|max:5120',
            'gallery_files.*' => 'nullable|image|max:5120',
            'images_text' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('thumbnail_file')) {
            $data['thumbnail'] = $this->uploader->upload($request->file('thumbnail_file'), 'products');
        } elseif ($product) {
            $data['thumbnail'] = $data['thumbnail'] ?? $product->thumbnail;
        }

        $images = $product?->images ?? [];
        if ($request->filled('images_text')) {
            $images = array_filter(array_map('trim', explode("\n", $request->images_text)));
        }
        if ($request->hasFile('gallery_files')) {
            $uploaded = $this->uploader->uploadMultiple($request->file('gallery_files'), 'products');
            $images = array_merge($images, $uploaded);
        }
        $data['images'] = $images;

        unset($data['images_text'], $data['thumbnail_file'], $data['gallery_files']);

        return $data;
    }
}
