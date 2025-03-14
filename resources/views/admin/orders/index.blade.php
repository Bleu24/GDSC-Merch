@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Orders</h1>

    @if($orders->isEmpty())
        <p class="text-gray-600">No orders found.</p>
    @else
        @foreach($orders as $order)
        <div class="bg-white shadow-lg rounded-lg p-6 mb-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Order #{{ $order->id }}</h2>
            <p class="text-gray-600"><strong>Status:</strong> 
                <span class="px-2 py-1 rounded 
                    {{ $order->status == 'PENDING' ? 'bg-yellow-400' : ($order->status == 'PROCESSING' ? 'bg-blue-400' : ($order->status == 'SHIPPED' ? 'bg-purple-400' : 'bg-green-400')) }} 
                    text-white">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
            <p class="text-gray-600"><strong>Total:</strong> ₱{{ number_format($order->total, 2) }}</p>
            <p class="text-gray-600"><strong>Ordered On:</strong> {{ $order->created_at->format('F j, Y, g:i A') }}</p>

            <h3 class="mt-4 text-md font-semibold">Items:</h3>
            <ul class="mt-2 space-y-2">
                @foreach($order->orderItems as $item)
                <li class="flex justify-between items-center border-b pb-2">
                    <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                    <span class="text-gray-700 font-semibold">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                </li>
                @endforeach
            </ul>

            <!-- ✅ Admin Update Order Status -->
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <label for="status" class="block text-sm font-medium text-gray-700">Update Status:</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="PENDING" {{ $order->status == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="PROCESSING" {{ $order->status == 'PROCESSING' ? 'selected' : '' }}>Processing</option>
                    <option value="SHIPPED" {{ $order->status == 'SHIPPED' ? 'selected' : '' }}>Shipped</option>
                    <option value="DELIVERED" {{ $order->status == 'DELIVERED' ? 'selected' : '' }}>Delivered</option>
                </select>
                <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Update Order
                </button>
            </form>

            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="mt-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="mt-2 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this order?')">
                    Delete Order
                </button>
            </form>
            
        </div>
        @endforeach
    @endif
</div>
@endsection
