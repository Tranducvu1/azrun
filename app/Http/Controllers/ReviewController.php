<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string|max:2000',
        ]);

        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => Auth::id()],
            array_merge($data, ['is_approved' => true])
        );

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}
