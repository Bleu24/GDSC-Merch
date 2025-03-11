<!DOCTYPE html>
<html lang="en">
<head>
    <title>Products - GDSC Merch Haven</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">

    @include('partials.navbar')

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Browse Our Products</h1>

        <!-- Category Filter & Search Form -->
        <form method="GET" action="{{ route('products.index') }}" class="flex space-x-4 mb-6">
            <select name="category" class="border rounded p-2">
                <option value="">All Categories</option>
                @foreach(\App\Models\Product::categories() as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" class="border rounded p-2">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($products as $product)
                <div class="border p-4 rounded-lg shadow">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    <h3 class="text-lg font-semibold mt-2">{{ $product->name }}</h3>
                    <p class="text-gray-600">{{ $product->description }}</p>
                    <p class="font-bold text-green-600 mt-2">{{ $product->formatted_price }}</p>
                    <button 
                        class="mt-2 w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 addToCart"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-image="{{ asset('storage/' . $product->image) }}"
                        data-price="{{ $product->price }}">
                        Add to Cart
                    </button>
                </div>
            @empty
                <p class="col-span-3 text-center">No products found.</p>
            @endforelse
        </div>
    </div>
</body>
</html>