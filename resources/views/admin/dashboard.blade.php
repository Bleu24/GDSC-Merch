@extends('layouts.admin')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
    <p class="text-lg">Total Products: <strong>{{ $productCount }}</strong></p>

    <div class="mt-6">
        <a href="{{ route('admin.products.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Manage Products</a>
    </div>
@endsection
