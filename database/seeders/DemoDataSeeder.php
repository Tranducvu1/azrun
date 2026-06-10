<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin SportShop',
            'email' => 'admin@sportshop.vn',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'phone' => '0901234567',
        ]);

        // Demo customer
        User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '0912345678',
        ]);

        // === CATEGORIES (theo imsports.vn) ===
        $parentCategories = [
            ['name' => 'Giày Nam', 'slug' => 'giay-nam', 'sort_order' => 1, 'image' => 'https://placehold.co/400x300/1a1a2e/ffffff?text=Giay+Nam'],
            ['name' => 'Giày Nữ', 'slug' => 'giay-nu', 'sort_order' => 2, 'image' => 'https://placehold.co/400x300/16213e/ffffff?text=Giay+Nu'],
            ['name' => 'Running Gears', 'slug' => 'running-gears', 'sort_order' => 3, 'image' => 'https://placehold.co/400x300/0f3460/ffffff?text=Running+Gears'],
            ['name' => 'Đồng Hồ GPS', 'slug' => 'dong-ho-gps', 'sort_order' => 4, 'image' => 'https://placehold.co/400x300/533483/ffffff?text=Dong+Ho+GPS'],
            ['name' => 'Dinh Dưỡng', 'slug' => 'dinh-duong', 'sort_order' => 5, 'image' => 'https://placehold.co/400x300/e94560/ffffff?text=Dinh+Duong'],
            ['name' => 'Phục Hồi', 'slug' => 'phuc-hoi', 'sort_order' => 6, 'image' => 'https://placehold.co/400x300/1a1a2e/ffffff?text=Phuc+Hoi'],
            ['name' => 'Triathlon', 'slug' => 'triathlon', 'sort_order' => 7, 'image' => 'https://placehold.co/400x300/16213e/ffffff?text=Triathlon'],
        ];

        $parentIds = [];
        foreach ($parentCategories as $cat) {
            $parentIds[$cat['slug']] = Category::create($cat)->id;
        }

        $childCategories = [
            ['parent_id' => $parentIds['giay-nam'], 'name' => 'Giày Chạy Bộ Đường', 'slug' => 'giay-chay-bo-duong', 'sort_order' => 1],
            ['parent_id' => $parentIds['giay-nam'], 'name' => 'Giày Chạy Trail', 'slug' => 'giay-chay-trail-nam', 'sort_order' => 2],
            ['parent_id' => $parentIds['giay-nam'], 'name' => 'Giày Leo Núi', 'slug' => 'giay-leo-nui-nam', 'sort_order' => 3],
            ['parent_id' => $parentIds['giay-nam'], 'name' => 'Giày Lifestyle Nam', 'slug' => 'giay-lifestyle-nam', 'sort_order' => 4],
            ['parent_id' => $parentIds['giay-nu'], 'name' => 'Giày Chạy Bộ Nữ', 'slug' => 'giay-chay-bo-nu', 'sort_order' => 1],
            ['parent_id' => $parentIds['giay-nu'], 'name' => 'Giày Chạy Trail Nữ', 'slug' => 'giay-chay-trail-nu', 'sort_order' => 2],
            ['parent_id' => $parentIds['running-gears'], 'name' => 'Kính Chạy Bộ', 'slug' => 'kinh-chay-bo', 'sort_order' => 1],
            ['parent_id' => $parentIds['running-gears'], 'name' => 'Mũ Chạy Bộ', 'slug' => 'mu-chay-bo', 'sort_order' => 2],
            ['parent_id' => $parentIds['running-gears'], 'name' => 'Tất Chạy Bộ', 'slug' => 'tat-chay-bo', 'sort_order' => 3],
            ['parent_id' => $parentIds['running-gears'], 'name' => 'Balo Chứa Nước', 'slug' => 'balo-chua-nuoc', 'sort_order' => 4],
            ['parent_id' => $parentIds['running-gears'], 'name' => 'Đèn Đội Đầu', 'slug' => 'den-doi-dau', 'sort_order' => 5],
            ['parent_id' => $parentIds['dong-ho-gps'], 'name' => 'Coros', 'slug' => 'coros', 'sort_order' => 1],
            ['parent_id' => $parentIds['dong-ho-gps'], 'name' => 'Garmin', 'slug' => 'garmin', 'sort_order' => 2],
            ['parent_id' => $parentIds['dong-ho-gps'], 'name' => 'Suunto', 'slug' => 'suunto', 'sort_order' => 3],
            ['parent_id' => $parentIds['dinh-duong'], 'name' => 'Energy Gel', 'slug' => 'energy-gel', 'sort_order' => 1],
            ['parent_id' => $parentIds['dinh-duong'], 'name' => 'Thanh Năng Lượng', 'slug' => 'thanh-nang-luong', 'sort_order' => 2],
            ['parent_id' => $parentIds['dinh-duong'], 'name' => 'Electrolytes', 'slug' => 'electrolytes', 'sort_order' => 3],
            ['parent_id' => $parentIds['phuc-hoi'], 'name' => 'Băng Dán Cơ', 'slug' => 'bang-dan-co', 'sort_order' => 1],
            ['parent_id' => $parentIds['phuc-hoi'], 'name' => 'Bó Gối', 'slug' => 'bo-goi', 'sort_order' => 2],
            ['parent_id' => $parentIds['phuc-hoi'], 'name' => 'Dép Phục Hồi', 'slug' => 'dep-phuc-hoi', 'sort_order' => 3],
        ];

        foreach ($childCategories as $child) {
            Category::create($child);
        }

        // === BRANDS (theo imsports.vn) ===
        $brands = [
            ['name' => 'Hoka', 'slug' => 'hoka', 'logo' => 'https://placehold.co/200x100/e94560/ffffff?text=HOKA'],
            ['name' => 'Salomon', 'slug' => 'salomon', 'logo' => 'https://placehold.co/200x100/1a1a2e/ffffff?text=SALOMON'],
            ['name' => 'On Running', 'slug' => 'on-running', 'logo' => 'https://placehold.co/200x100/0f3460/ffffff?text=ON'],
            ['name' => '2XU', 'slug' => '2xu', 'logo' => 'https://placehold.co/200x100/533483/ffffff?text=2XU'],
            ['name' => 'Coros', 'slug' => 'coros', 'logo' => 'https://placehold.co/200x100/16213e/ffffff?text=COROS'],
            ['name' => 'Norda', 'slug' => 'norda', 'logo' => 'https://placehold.co/200x100/e94560/ffffff?text=NORDA'],
            ['name' => 'Inov8', 'slug' => 'inov8', 'logo' => 'https://placehold.co/200x100/0f3460/ffffff?text=INOV8'],
            ['name' => 'GU Energy', 'slug' => 'gu-energy', 'logo' => 'https://placehold.co/200x100/533483/ffffff?text=GU'],
            ['name' => 'T8', 'slug' => 't8', 'logo' => 'https://placehold.co/200x100/1a1a2e/ffffff?text=T8'],
            ['name' => 'Fractel', 'slug' => 'fractel', 'logo' => 'https://placehold.co/200x100/16213e/ffffff?text=FRACTEL'],
            ['name' => 'Zoot', 'slug' => 'zoot', 'logo' => 'https://placehold.co/200x100/e94560/ffffff?text=ZOOT'],
            ['name' => 'Nitecore', 'slug' => 'nitecore', 'logo' => 'https://placehold.co/200x100/0f3460/ffffff?text=NITECORE'],
            ['name' => 'Shokz', 'slug' => 'shokz', 'logo' => 'https://placehold.co/200x100/533483/ffffff?text=SHOKZ'],
            ['name' => 'Buff', 'slug' => 'buff', 'logo' => 'https://placehold.co/200x100/1a1a2e/ffffff?text=BUFF'],
            ['name' => 'Injinji', 'slug' => 'injinji', 'logo' => 'https://placehold.co/200x100/16213e/ffffff?text=INJINJI'],
            ['name' => 'Pro-Tec', 'slug' => 'pro-tec', 'logo' => 'https://placehold.co/200x100/e94560/ffffff?text=PRO-TEC'],
            ['name' => 'Saucony', 'slug' => 'saucony', 'logo' => 'https://placehold.co/200x100/0f3460/ffffff?text=SAUCONY'],
        ];

        $brandIds = [];
        foreach ($brands as $brand) {
            $brandIds[$brand['slug']] = Brand::create($brand)->id;
        }

        // === PRODUCTS (theo imsports.vn) ===
        $trailCat = Category::where('slug', 'giay-chay-trail-nam')->first()->id;
        $roadCat = Category::where('slug', 'giay-chay-bo-duong')->first()->id;
        $gearCap = Category::where('slug', 'mu-chay-bo')->first()->id;
        $gearGel = Category::where('slug', 'energy-gel')->first()->id;
        $recoveryCat = Category::where('slug', 'bo-goi')->first()->id;
        $watchCat = Category::where('slug', 'coros')->first()->id;
        $gearSock = Category::where('slug', 'tat-chay-bo')->first()->id;
        $gearLamp = Category::where('slug', 'den-doi-dau')->first()->id;
        $triCat = Category::where('slug', 'triathlon')->first()->id;
        $gearKinh = Category::where('slug', 'kinh-chay-bo')->first()->id;
        $depCat = Category::where('slug', 'dep-phuc-hoi')->first()->id;
        $gearBag = Category::where('slug', 'balo-chua-nuoc')->first()->id;
        $barCat = Category::where('slug', 'thanh-nang-luong')->first()->id;
        $tapeCat = Category::where('slug', 'bang-dan-co')->first()->id;

        $products = [
            [
                'name' => 'Speedgoat 6 | Giày Chạy Địa Hình Nam Hoka Speedgoat 6',
                'slug' => 'hoka-speedgoat-6',
                'category_id' => $trailCat, 'brand_id' => $brandIds['hoka'],
                'price' => 3999000, 'sale_price' => 2799300,
                'sale_from' => now()->subDays(5), 'sale_to' => now()->addDays(30),
                'sku' => 'HOKA-SG6-001', 'stock_quantity' => 25,
                'thumbnail' => 'https://placehold.co/600x600/e94560/ffffff?text=Speedgoat+6',
                'images' => ['https://placehold.co/600x600/e94560/ffffff?text=SG6+1', 'https://placehold.co/600x600/1a1a2e/ffffff?text=SG6+2', 'https://placehold.co/600x600/0f3460/ffffff?text=SG6+3'],
                'is_featured' => true, 'is_active' => true,
                'short_description' => 'Giày trail iconic với đế Vibram Megagrip siêu bám, lớp đệm EVA tối ưu cho đường dài.',
                'description' => '<p>Hoka Speedgoat 6 là thế hệ mới nhất của dòng giày trail chạy địa hình bán chạy nhất. Với lớp đệm EVA dày, đế Vibram Megagrip 5mm lug, và thân giày nhẹ bền - đôi giày hoàn hảo cho ultra trail.</p>',
                'attributes' => ['weight' => '298g', 'drop' => '4mm', 'surface' => 'Trail'],
                'sold_count' => 150, 'views_count' => 3200,
            ],
            [
                'name' => 'Mafate Speed 4 | Giày Chạy Địa Hình Nam Hoka Mafate Speed 4',
                'slug' => 'hoka-mafate-speed-4',
                'category_id' => $trailCat, 'brand_id' => $brandIds['hoka'],
                'price' => 3999000, 'sale_price' => 2399400,
                'sale_from' => now()->subDays(3), 'sale_to' => now()->addDays(20),
                'sku' => 'HOKA-MS4-001', 'stock_quantity' => 15,
                'thumbnail' => 'https://placehold.co/600x600/1a1a2e/ffffff?text=Mafate+Speed+4',
                'images' => ['https://placehold.co/600x600/1a1a2e/ffffff?text=MS4+1', 'https://placehold.co/600x600/e94560/ffffff?text=MS4+2'],
                'is_featured' => true, 'is_active' => true,
                'short_description' => 'Giày trail siêu nhẹ với công nghệ ProFly+ midsole, đế Vibram Megagrip bám đường tuyệt đối.',
                'description' => '<p>Mafate Speed 4 mang đến trải nghiệm trail chạy nhẹ nhàng nhất với đệm ProFly+, thích hợp cho cả đường dốc và kỹ thuật.</p>',
                'attributes' => ['weight' => '265g', 'drop' => '4mm', 'surface' => 'Trail'],
                'sold_count' => 89, 'views_count' => 2100,
            ],
            [
                'name' => 'Norda 001 | Giày Chạy Địa Hình Nam Norda 001',
                'slug' => 'norda-001',
                'category_id' => $trailCat, 'brand_id' => $brandIds['norda'],
                'price' => 6950000, 'sale_price' => 5907500,
                'sale_from' => now()->subDays(1), 'sale_to' => now()->addDays(14),
                'sku' => 'NORDA-001-001', 'stock_quantity' => 8,
                'thumbnail' => 'https://placehold.co/600x600/533483/ffffff?text=Norda+001',
                'images' => ['https://placehold.co/600x600/533483/ffffff?text=N001+1', 'https://placehold.co/600x600/0f3460/ffffff?text=N001+2'],
                'is_featured' => true, 'is_active' => true,
                'short_description' => 'Giày trail cao cấp nhất với thân Dyneema siêu bền, đế Vibram Megagrip, sản xuất tại Canada.',
                'description' => '<p>Norda 001 là giày trail cao cấp với thân giày làm từ sợi Dyneema - mạnh hơn steel weight-for-weight. Đế Vibram Megagrip cùng lớp đệm ESS foam đáp ứng mọi địa hình.</p>',
                'attributes' => ['weight' => '275g', 'drop' => '5mm', 'surface' => 'Trail'],
                'sold_count' => 42, 'views_count' => 1800,
            ],
            [
                'name' => 'Quần Bó Cơ Nam 2XU Light Speed Compression Shorts',
                'slug' => '2xu-light-speed-mens',
                'category_id' => $trailCat, 'brand_id' => $brandIds['2xu'],
                'price' => 1999000, 'sale_price' => null,
                'sku' => '2XU-LS-M-001', 'stock_quantity' => 50,
                'thumbnail' => 'https://placehold.co/600x600/16213e/ffffff?text=2XU+Compression',
                'images' => ['https://placehold.co/600x600/16213e/ffffff?text=2XU+1'],
                'is_featured' => true, 'is_active' => true,
                'short_description' => 'Quần bó cơ compression hỗ trợ phục hồi cơ, giảm chấn thương khi chạy bộ đường dài.',
                'description' => '<p>2XU Light Speed Compression Shorts với công nghệ PWX fabric tăng cường tuần hoàn máu, giảm mỏi cơ và tăng hiệu suất chạy.</p>',
                'attributes' => ['material' => 'PWX Fabric', 'compression' => 'Graduated', 'type' => 'Shorts'],
                'sold_count' => 200, 'views_count' => 4500,
            ],
            [
                'name' => 'Đồng hồ chạy bộ GPS Coros Pace 4 - Jakob Ingebrigtsen',
                'slug' => 'coros-pace-4-jakob',
                'category_id' => $watchCat, 'brand_id' => $brandIds['coros'],
                'price' => 8200000, 'sale_price' => null,
                'sku' => 'COROS-P4-JE-001', 'stock_quantity' => 12,
                'thumbnail' => 'https://placehold.co/600x600/0f3460/ffffff?text=Coros+Pace+4',
                'images' => ['https://placehold.co/600x600/0f3460/ffffff?text=CP4+1', 'https://placehold.co/600x600/16213e/ffffff?text=CP4+2'],
                'is_featured' => true, 'is_active' => true,
                'short_description' => 'Đồng hồ GPS chạy bộ thế hệ mới với pin 20 ngày, màn hình AMOLED, tracking nâng cao.',
                'description' => '<p>Coros Pace 4 phiên bản Jakob Ingebrigtsen với mặt kính sapphire, GPS dual-frequency, và thời lượng pin lên đến 20 ngày sử dụng thường.</p>',
                'attributes' => ['battery' => '20 days', 'display' => 'AMOLED', 'gps' => 'Dual-frequency'],
                'sold_count' => 65, 'views_count' => 5600,
            ],
            [
                'name' => 'Mũ Chạy Bộ Trùm Gáy Fractel Legionnaire - Spectrum',
                'slug' => 'fractel-legionnaire-spectrum',
                'category_id' => $gearCap, 'brand_id' => $brandIds['fractel'],
                'price' => 1560000, 'sale_price' => null,
                'sku' => 'FRA-LEG-SP-001', 'stock_quantity' => 30,
                'thumbnail' => 'https://placehold.co/600x600/e94560/ffffff?text=Fractel+Leg',
                'images' => ['https://placehold.co/600x600/e94560/ffffff?text=FRA+1'],
                'is_featured' => false, 'is_active' => true,
                'short_description' => 'Mũ chạy trail trùm gái chống nắng siêu nhẹ, thấm hút mồ hôi tốt.',
                'description' => '<p>Fractel Legionnaire bảo vệ toàn diện cho đầu và gáy dưới nắng trail, chất liệu thoáng khí nhanh khô.</p>',
                'attributes' => ['weight' => '55g', 'material' => 'Quick-dry', 'type' => 'Legionnaire'],
                'sold_count' => 78, 'views_count' => 1200,
            ],
            [
                'name' => 'Gói Gel năng lượng GU Energy Gel Roctane - Strawberry Kiwi',
                'slug' => 'gu-roctane-strawberry-kiwi',
                'category_id' => $gearGel, 'brand_id' => $brandIds['gu-energy'],
                'price' => 75000, 'sale_price' => null,
                'sku' => 'GU-ROC-SK-001', 'stock_quantity' => 200,
                'thumbnail' => 'https://placehold.co/600x600/533483/ffffff?text=GU+Roctane',
                'images' => ['https://placehold.co/600x600/533483/ffffff?text=GU+1'],
                'is_featured' => false, 'is_active' => true,
                'short_description' => 'Gel năng lượng cao cấp GU Roctane với 22g carbohydrate, caffeine và electrolytes.',
                'description' => '<p>GU Roctane Energy Gel chứa công thức nâng cao với 22g carbohydrate, 35mg caffeine, amino acid và sodium giúp duy trì năng lượng trong chạy ultra.</p>',
                'attributes' => ['carbs' => '22g', 'caffeine' => '35mg', 'servings' => '1'],
                'sold_count' => 500, 'views_count' => 2300,
            ],
            [
                'name' => 'Bó Gối Chống Chấn Thương Thể Thao Pro-Tec X-Trac Knee Support',
                'slug' => 'pro-tec-x-trac-knee',
                'category_id' => $recoveryCat, 'brand_id' => $brandIds['pro-tec'],
                'price' => 795000, 'sale_price' => null,
                'sku' => 'PT-XT-KN-001', 'stock_quantity' => 40,
                'thumbnail' => 'https://placehold.co/600x600/1a1a2e/ffffff?text=ProTec+Knee',
                'images' => ['https://placehold.co/600x600/1a1a2e/ffffff?text=PT+1'],
                'is_featured' => true, 'is_active' => true,
                'short_description' => 'Bó gối thể thao hỗ trợ ổn định đầu gối, giảm đau và phòng chấn thương khi chạy.',
                'description' => '<p>Pro-Tec X-Trac Knee Support thiết kế công thái học ôm sát, hỗ trợ patella tracking, giảm áp lực lên đầu gối khi vận động mạnh.</p>',
                'attributes' => ['type' => 'Knee Support', 'sizes' => 'S/M/L/XL', 'material' => 'Neoprene'],
                'sold_count' => 180, 'views_count' => 3100,
            ],
            [
                'name' => 'Sạc dự phòng vỏ Carbon Nitecore NB10000 Gen4 Power Bank',
                'slug' => 'nitecore-nb10000-gen4',
                'category_id' => $gearLamp, 'brand_id' => $brandIds['nitecore'],
                'price' => 1850000, 'sale_price' => null,
                'sku' => 'NC-NB10G4-001', 'stock_quantity' => 20,
                'thumbnail' => 'https://placehold.co/600x600/0f3460/ffffff?text=Nitecore+NB10K',
                'images' => ['https://placehold.co/600x600/0f3460/ffffff?text=NC+1'],
                'is_featured' => true, 'is_active' => true,
                'short_description' => 'Sạc dự phòng 10000mAh vỏ carbon siêu nhẹ 149g, sạc nhanh 20W PD.',
                'description' => '<p>Nitecore NB10000 Gen4 với vỏ carbon fiber siêu bền nhẹ chỉ 149g, hỗ trợ sạc nhanh 20W Power Delivery, hoàn hảo cho trail runner.</p>',
                'attributes' => ['capacity' => '10000mAh', 'weight' => '149g', 'output' => '20W PD'],
                'sold_count' => 95, 'views_count' => 2800,
            ],
            [
                'name' => 'Đèn Đội Đầu Chạy Địa Hình Nitecore UT27 MCT 800 lumens',
                'slug' => 'nitecore-ut27-mct',
                'category_id' => $gearLamp, 'brand_id' => $brandIds['nitecore'],
                'price' => 1475000, 'sale_price' => null,
                'sku' => 'NC-UT27-001', 'stock_quantity' => 18,
                'thumbnail' => 'https://placehold.co/600x600/e94560/ffffff?text=Nitecore+UT27',
                'images' => ['https://placehold.co/600x600/e94560/ffffff?text=UT27+1'],
                'is_featured' => false, 'is_active' => true,
                'short_description' => 'Đèn chạy trail 800 lumens với chế độ Hybrid Beam, pin sạc USB-C siêu nhẹ.',
                'description' => '<p>Nitecore UT27 MCT với chế độ Hybrid Beam kết hợp spotlight và floodlight, 800 lumens sáng tối đa, pin sạc USB-C, chỉ nặng 85g.</p>',
                'attributes' => ['lumens' => '800', 'weight' => '85g', 'battery' => 'USB-C rechargeable'],
                'sold_count' => 60, 'views_count' => 1500,
            ],
            [
                'name' => 'Mũ Chạy Bộ Rộng Vành Fractel Bucket Hat - Spectrum',
                'slug' => 'fractel-bucket-spectrum',
                'category_id' => $gearCap, 'brand_id' => $brandIds['fractel'],
                'price' => 1560000, 'sale_price' => null,
                'sku' => 'FRA-BKT-SP-001', 'stock_quantity' => 25,
                'thumbnail' => 'https://placehold.co/600x600/16213e/ffffff?text=Fractel+Bucket',
                'images' => ['https://placehold.co/600x600/16213e/ffffff?text=BKT+1'],
                'is_featured' => false, 'is_active' => true,
                'short_description' => 'Mũ bucket chạy bộ rộng vành chống nắng, chất liệu thoáng khí nhanh khô.',
                'description' => '<p>Fractel Bucket Hat Spectrum bảo vệ toàn diện khỏi nắng với vành rộng, chất liệu nhẹ thoáng khí.</p>',
                'attributes' => ['weight' => '60g', 'material' => 'Quick-dry', 'type' => 'Bucket'],
                'sold_count' => 55, 'views_count' => 980,
            ],
            [
                'name' => 'Bộ Quần Áo Ba Môn Nam Zoot Men\'s Ultra Tri P1 Racesuit',
                'slug' => 'zoot-ultra-tri-p1-mens',
                'category_id' => $triCat, 'brand_id' => $brandIds['zoot'],
                'price' => 8990000, 'sale_price' => null,
                'sku' => 'ZOOT-UTRIP1-M-001', 'stock_quantity' => 5,
                'thumbnail' => 'https://placehold.co/600x600/533483/ffffff?text=Zoot+Tri+Mens',
                'images' => ['https://placehold.co/600x600/533483/ffffff?text=ZOOT+1'],
                'is_featured' => false, 'is_active' => true,
                'short_description' => 'Bộ quần áo ba môn cao cấp Zoot Ultra Tri P1 với chamois PRO, hydrodynamic coating.',
                'description' => '<p>Zoot Ultra Tri P1 Racesuit với lớp phủ hydrodynamic giảm drag, chamois PRO ấn, và thân Suit saver cho phép bơi mà không cần thay đồ.</p>',
                'attributes' => ['material' => 'ENDURO fabric', 'chamois' => 'PRO', 'type' => 'Trisuit'],
                'sold_count' => 15, 'views_count' => 620,
            ],
            [
                'name' => 'Thanh năng lượng tự nhiên Lecka Energy Bar - Chuối Dứa',
                'slug' => 'lecka-energy-bar-chuoi-dua',
                'category_id' => $barCat, 'brand_id' => $brandIds['gu-energy'],
                'price' => 40000, 'sale_price' => null,
                'sku' => 'LECKA-EB-CD-001', 'stock_quantity' => 300,
                'thumbnail' => 'https://placehold.co/600x600/e94560/ffffff?text=Lecka+Bar',
                'images' => ['https://placehold.co/600x600/e94560/ffffff?text=LK+1'],
                'is_featured' => false, 'is_active' => true,
                'short_description' => 'Thanh năng lượng 100% nguyên liệu tự nhiên, vị chuối dứa thơm ngon.',
                'description' => '<p>Lecka Energy Bar làm từ chuối sấy, dứa sấy, yến mạch và mật ong - nguồn năng lượng tự nhiên cho runner.</p>',
                'attributes' => ['calories' => '180kcal', 'weight' => '45g', 'type' => 'Energy Bar'],
                'sold_count' => 800, 'views_count' => 1200,
            ],
            [
                'name' => 'Áo Chạy Bộ Nam On Running Men\'s Performance-T',
                'slug' => 'on-running-performance-t-mens',
                'category_id' => $roadCat, 'brand_id' => $brandIds['on-running'],
                'price' => 2060000, 'sale_price' => null,
                'sku' => 'ON-PERF-T-M-001', 'stock_quantity' => 35,
                'thumbnail' => 'https://placehold.co/600x600/0f3460/ffffff?text=ON+Perf+T',
                'images' => ['https://placehold.co/600x600/0f3460/ffffff?text=ON+1'],
                'is_featured' => true, 'is_active' => true,
                'short_description' => 'Áo chạy bộ nhẹ, thấm hút mồ hôi, công nghệON ựa siêu thoáng.',
                'description' => '<p>On Running Performance-T với chất liệu nhẹ, thấm hút mồ hôi nhanh, thiết kế ergonomic tối ưu chuyển động.</p>',
                'attributes' => ['weight' => '110g', 'material' => 'Recycled Polyester', 'type' => 'T-Shirt'],
                'sold_count' => 120, 'views_count' => 2800,
            ],
            [
                'name' => 'Tai Nghe Thể Thao Truyền Âm Qua Xương Shokz OpenRun Pro',
                'slug' => 'shokz-openrun-pro',
                'category_id' => $watchCat, 'brand_id' => $brandIds['shokz'],
                'price' => 4990000, 'sale_price' => 3992000,
                'sale_from' => now()->subDays(7), 'sale_to' => now()->addDays(10),
                'sku' => 'SHOKZ-ORP-001', 'stock_quantity' => 10,
                'thumbnail' => 'https://placehold.co/600x600/1a1a2e/ffffff?text=Shokz+OpenRun',
                'images' => ['https://placehold.co/600x600/1a1a2e/ffffff?text=SHOKZ+1'],
                'is_featured' => true, 'is_active' => true,
                'short_description' => 'Tai nghe chạy bộ truyền âm qua xương, mở tai nghe môi trường xung quanh, chống nước IP55.',
                'description' => '<p>Shokz OpenRun Pro với công nghệ bone conduction cho phép nghe nhạc nhưng vẫn nhận âm thanh môi trường, cực an toàn khi chạy ngoài.</p>',
                'attributes' => ['battery' => '10 hours', 'water' => 'IP55', 'type' => 'Bone Conduction'],
                'sold_count' => 88, 'views_count' => 4200,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Product Variants (size cho giày)
        $shoeProducts = Product::where('attributes->weight', 'like', '%g')->limit(3)->get();
        $sizes = ['39', '40', '41', '42', '43', '44'];
        foreach ($shoeProducts as $product) {
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $product->sku . '-' . $size,
                    'name' => "Size $size",
                    'price' => $product->sale_price ?? $product->price,
                    'stock_quantity' => rand(2, 10),
                    'attributes' => ['size' => $size],
                    'is_active' => true,
                ]);
            }
        }

        // === BANNERS ===
        $banners = [
            ['title' => 'New Collection On Running', 'image' => 'https://placehold.co/1200x400/0f3460/ffffff?text=ON+RUNNING+NEW+COLLECTION', 'link' => '/danh-muc/giay-chay-bo-duong', 'sort_order' => 1, 'is_active' => true],
            ['title' => 'Flash Sale Hoka', 'image' => 'https://placehold.co/1200x400/e94560/ffffff?text=HOKA+FLASH+SALE+30%25', 'link' => '/danh-muc/giay-chay-trail-nam', 'sort_order' => 2, 'is_active' => true],
            ['title' => 'Coros Pace 4', 'image' => 'https://placehold.co/1200x400/16213e/ffffff?text=COROS+PACE+4+NEW', 'link' => '/danh-muc/coros', 'sort_order' => 3, 'is_active' => true],
        ];
        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        // === COUPONS ===
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percent',
            'value' => 10,
            'min_order_amount' => 500000,
            'usage_limit' => 100,
            'used_count' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'RUN50K',
            'type' => 'fixed',
            'value' => 50000,
            'min_order_amount' => 1000000,
            'usage_limit' => 50,
            'used_count' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addMonths(1),
            'is_active' => true,
        ]);

        // === POSTS ===
        Post::create([
            'title' => 'Những đôi giày chạy trail đáng mong đợi nhất 2026',
            'slug' => 'giay-chay-trail-2026',
            'excerpt' => 'Tổng hợp các đôi giày trail chạy hot nhất năm 2026 từ Hoka, Salomon, Norda...',
            'content' => '<p>Năm 2026 hứa hẹn nhiều đôi giày trail chạy đẳng cấp. Từ Hoka Speedgoat 6, Norda 002 cho đến Salomon Ultra Glide...</p>',
            'thumbnail' => 'https://placehold.co/800x400/1a1a2e/ffffff?text=Trail+Shoes+2026',
            'user_id' => 1,
            'is_published' => true,
            'published_at' => now()->subDays(60),
        ]);

        Post::create([
            'title' => 'Hướng dẫn sử dụng băng dán cơ đúng cách',
            'slug' => 'huong-dan-bang-dan-co',
            'excerpt' => 'Cách dán băng cơ đúng kỹ thuật để hỗ trợ chấn thương và phòng ngừa khi chạy bộ.',
            'content' => '<p>Băng dán cơ (kinesiology tape) là công cụ hỗ trợ phục hồi chấn thương hiệu quả. Bài viết hướng dẫn kỹ thuật dán đúng cách cho runner...</p>',
            'thumbnail' => 'https://placehold.co/800x400/0f3460/ffffff?text=Kinesiology+Tape',
            'user_id' => 1,
            'is_published' => true,
            'published_at' => now()->subDays(45),
        ]);
    }
}
