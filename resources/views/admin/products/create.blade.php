@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-4">Add Product</h1>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block">Name</label>
                <input type="text" name="name" class="border w-full p-2">
            </div>

            <div>
                <label class="block">Description</label>
                <textarea name="description" class="border w-full p-2"></textarea>
            </div>

            <div>
                <label class="block">Price</label>
                <input type="number" step="0.01" name="price" class="border w-full p-2">
            </div>

            <div>
                <label class="block">Category</label>
                <select name="category" class="border w-full p-2">
                    @foreach (\App\Models\Product::categories() as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block">Stock</label>
                <input type="number" name="stock" class="border w-full p-2">
            </div>

            <div>
                <label class="block">Product Image</label>
                <input type="file" name="image" class="border w-full p-2" onchange="previewImage(event)">
                <img id="preview" src="#" alt="Image Preview" class="mt-2 hidden" style="max-width: 200px;">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Product</button>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('preview');
            preview.src = reader.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
