<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ImageCatalogService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->publishSiteLogo();

        $catalog = require database_path('data/product-catalog.php');
        $images = app(ImageCatalogService::class);

        $categories = [];
        foreach ($catalog['categories'] as $cat) {
            $categories[$cat['slug']] = Category::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'sort_order' => $cat['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        $brands = [];
        foreach ($catalog['brands'] as $brand) {
            $brands[$brand['slug']] = Brand::updateOrCreate(
                ['slug' => $brand['slug']],
                [
                    'name' => $brand['name'],
                    'description' => $brand['description'],
                    'is_active' => true,
                ]
            );
        }

        foreach ($catalog['products'] as $item) {
            $published = $images->publishForProduct($item['slug'], $item['images']);

            $product = Product::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'short_description' => $item['short_description'],
                    'description' => $item['description'],
                    'category_id' => $categories[$item['category']]->id,
                    'brand_id' => $brands[$item['brand']]->id,
                    'price' => $item['price'],
                    'sale_price' => $item['sale_price'],
                    'sku' => $item['sku'],
                    'stock_quantity' => $item['stock'],
                    'thumbnail' => $published['thumbnail'],
                    'images' => $published['images'],
                    'attributes' => $item['attributes'],
                    'is_featured' => $item['is_featured'],
                    'is_active' => true,
                    'sold_count' => random_int(5, 120),
                    'views_count' => random_int(50, 800),
                ]
            );

            if (!empty($item['sizes'])) {
                ProductVariant::where('product_id', $product->id)->delete();
                foreach ($item['sizes'] as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $item['sku'] . '-' . strtoupper($size),
                        'name' => $size,
                        'price' => $item['sale_price'] ?? $item['price'],
                        'stock_quantity' => (int) ($item['stock'] / max(count($item['sizes']), 1)),
                        'is_active' => true,
                    ]);
                }
            }
        }

        // Gán ảnh danh mục từ sản phẩm featured đầu tiên trong mỗi hạng mục
        foreach ($categories as $category) {
            $thumb = Product::where('category_id', $category->id)
                ->whereNotNull('thumbnail')
                ->orderByDesc('is_featured')
                ->orderBy('id')
                ->value('thumbnail');

            if ($thumb) {
                $category->update(['image' => $thumb]);
            }
        }

        foreach ($catalog['banners'] ?? [] as $banner) {
            $imageUrl = $images->publish('banners', $banner['image']);
            if ($imageUrl) {
                Banner::updateOrCreate(
                    ['title' => $banner['title']],
                    [
                        'image' => $imageUrl,
                        'link' => $banner['link'],
                        'description' => $banner['description'],
                        'sort_order' => $banner['sort_order'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }

    private function publishSiteLogo(): void
    {
        $source = base_path('image/logo/405265740_208585412290069_914724016516589255_n.jpg');
        if (!File::exists($source)) {
            return;
        }

        File::ensureDirectoryExists(storage_path('app/public/logo'));
        File::copy($source, storage_path('app/public/logo/azrun.jpg'));
    }
}
