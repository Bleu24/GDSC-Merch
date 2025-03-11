<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product; // Make sure your model name is correct (ideally Product, not Products)

class ProductController extends Controller
{
    /**
     * Public product listing page (for users).
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('category') && in_array($request->category, Product::categories())) {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->get();

        return view('products.index', compact('products'));
    }

    /**
     * Admin product listing page (for GDSC officers/admins).
     */
    public function adminIndex()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show form to create new product.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'required|image',
            'category' => 'required|in:' . implode(',', Product::categories()),
            'stock' => 'required|integer|min:0',
        ]);

        $validated['image'] = $request->file('image')->store('products', 'public');

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully!');
    }

    /**
     * Show form to edit existing product.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update existing product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image',
            'category' => 'required|in:' . implode(',', Product::categories()),
            'stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
