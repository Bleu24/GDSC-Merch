@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-4">Edit Product</h1>

    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="space-y-4">

            <!-- Current Image Preview -->
            <div>
                <label class="block font-medium">Current Product Image</label>
                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="h-48 w-48 object-cover rounded border">
            </div>
            
            <div>
                <label class="block">Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="border w-full p-2">
            </div>

            <div>
                <label class="block">Description</label>
                <textarea name="description" class="border w-full p-2">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="border w-full p-2">
            </div>

            <div>
                <label class="block">Category</label>
                <select name="category" class="border w-full p-2">
                    @foreach (\App\Models\Product::categories() as $category)
                        <option value="{{ $category }}" @selected($product->category === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="border w-full p-2">
            </div>

            <div>
                <label class="block">Product Image (optional)</label>
                <input type="file" name="image" class="border w-full p-2">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Product</button>
        </div>
    </form>
</div>
@endsection
