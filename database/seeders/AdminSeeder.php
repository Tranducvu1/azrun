<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@sportshop.vn'],
            [
                'name' => 'Admin SportShop',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'phone' => '0901234567',
            ]
        );

        Brand::firstOrCreate(
            ['slug' => 'khac'],
            [
                'name' => 'Khác',
                'description' => 'Thương hiệu mặc định',
                'logo' => null,
                'is_active' => true,
            ]
        );
    }
}
