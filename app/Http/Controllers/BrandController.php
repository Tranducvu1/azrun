<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::active()->orderBy('name')->get();
        return view('brands', compact('brands'));
    }
}
