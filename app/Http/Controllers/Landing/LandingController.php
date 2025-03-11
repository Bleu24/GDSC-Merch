<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class LandingController extends Controller
{
    public function index()
    {
        // Fetch latest 6 products to display as featured
        $products = Product::latest()->take(6)->get();
        return view('landing', compact('products'));
    }
}
