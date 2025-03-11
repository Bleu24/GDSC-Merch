@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-4">Manage Products</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Add Product</a>

    <table class="mt-4 w-full border-collapse border border-gray-300 text-center align-middle">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2 text-center align-middle">Image</th>
                <th class="border px-4 py-2 text-center align-middle">Name</th>
                <th class="border px-4 py-2 text-center align-middle">Category</th>
                <th class="border px-4 py-2 text-center align-middle">Price</th>
                <th class="border px-4 py-2 text-center align-middle">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td class="border px-4 py-2 text-center align-middle">
                    <div class="relative inline-block">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="h-16 w-16 object-cover rounded cursor-pointer"
                             onmouseover="showPreview(event, '{{ asset('storage/' . $product->image) }}')"
                             onmouseout="hidePreview()">
                    </div>
                </td>
                <td class="border px-4 py-2 text-center align-middle">{{ $product->name }}</td>
                <td class="border px-4 py-2 text-center align-middle">{{ $product->category }}</td>
                <td class="border px-4 py-2 text-center align-middle">{{ $product->formatted_price }}</td>
                <td class="border px-4 py-2 text-center align-middle space-x-2">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-500">Edit</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Floating Image Preview -->
<div id="imagePreview"
     class="hidden fixed z-50 border border-gray-300 shadow-lg bg-white p-1 rounded"
     style="pointer-events: none;">
    <img src="" class="h-64 w-auto object-contain">
</div>

<script>
    function showPreview(event, imageUrl) {
        const preview = document.getElementById('imagePreview');
        const previewImage = preview.querySelector('img');
        previewImage.src = imageUrl;

        preview.style.left = (event.pageX + 20) + 'px'; // offset to the right of the cursor
        preview.style.top = (event.pageY - 20) + 'px';  // slightly above the cursor
        preview.classList.remove('hidden');
    }

    function hidePreview() {
        document.getElementById('imagePreview').classList.add('hidden');
    }
</script>
@endsection
