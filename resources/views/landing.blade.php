<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merch Business Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body class="bg-gray-100">
    
    @include('partials.navbar')

    @if (session('message'))
        <div 
            class="p-3 rounded-lg mb-4 text-center
            @if (session('status') === 'success') bg-green-500 text-white
            @elseif (session('status') === 'error') bg-red-500 text-white
            @elseif (session('status') === 'info') bg-blue-500 text-white
            @else bg-gray-500 text-white
            @endif"
        >
            {{ session('message') }}
        </div>
    @endif

    <section class="bg-blue-500 text-white text-center py-20">
        <h2 class="text-4xl font-bold">Welcome to GDSC Merch Haven</h2>
        <p class="mt-4 text-lg">Your one-stop shop for exclusive and stylish merchandise.</p>
        <a href="#shop" class="mt-6 inline-block bg-white text-blue-500 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">Shop Now</a>
    </section>

    <section id="shop" class="py-16 container mx-auto px-4">
        <h3 class="text-3xl font-bold text-center text-gray-800">Featured Merchandise</h3>
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
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
    </section>

    <section id="about" class="bg-gray-200 py-16 text-center">
        <h3 class="text-3xl font-bold text-gray-800">About GDCS Merch Haven</h3>
        <p class="mt-4 text-gray-600 max-w-2xl mx-auto">We create stylish and sustainable merchandise for our community. Every purchase supports local artists and eco-friendly production.</p>
    </section>

    <section id="contact" class="py-16 container mx-auto px-4">
        <h3 class="text-3xl font-bold text-center text-gray-800">Contact Us</h3>
        <form class="mt-8 max-w-lg mx-auto">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" id="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label for="message" class="block text-gray-700">Message</label>
                <textarea id="message" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Send Message</button>
        </form>
    </section>

    <footer class="bg-gray-800 text-white py-6 text-center">
        <p>&copy; 2024 GDSC Merch Haven. All rights reserved.</p>
    </footer>
</body>
</html>
