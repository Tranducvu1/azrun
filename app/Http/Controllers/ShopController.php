<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['category', 'brand']);

        // Category filter
        if ($categorySlug = $request->get('category')) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $categoryIds = Category::where('id', $category->id)
                ->orWhere('parent_id', $category->id)
                ->pluck('id');
            $query->whereIn('category_id', $categoryIds);
        }

        // Brand filter
        if ($brandSlug = $request->get('brand')) {
            $brand = Brand::where('slug', $brandSlug)->firstOrFail();
            $query->where('brand_id', $brand->id);
        }

        // Search
        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        // Price range
        if ($priceMin = $request->get('price_min')) {
            $query->where('price', '>=', $priceMin);
        }
        if ($priceMax = $request->get('price_max')) {
            $query->where('price', '<=', $priceMax);
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        $query = match ($sort) {
            'price-asc' => $query->orderBy('price', 'asc'),
            'price-desc' => $query->orderBy('price', 'desc'),
            'bestseller' => $query->orderBy('sold_count', 'desc'),
            'name-asc' => $query->orderBy('name', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::whereNull('parent_id')->active()->ordered()->with('children')->get();
        $brands = Brand::active()->orderBy('name')->get();

        return view('shop', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['category', 'brand', 'variants', 'reviews' => function ($q) {
                $q->where('is_approved', true)->latest();
            }])
            ->firstOrFail();

        // Increment views
        $product->increment('views_count');

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(8)
            ->get();

        return view('product-detail', compact('product', 'relatedProducts'));
    }
}
