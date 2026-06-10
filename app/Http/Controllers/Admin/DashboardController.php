<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'customers' => User::where('role', 'customer')->count(),
            'revenue' => Order::whereIn('status', ['confirmed', 'shipping', 'delivered'])->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $topProducts = Product::orderBy('sold_count', 'desc')->take(5)->get();
        $categories = Category::withCount('products')->orderBy('products_count', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'categories'));
    }
}
