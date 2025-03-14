@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Your Orders</h1>

    @if($orders->isEmpty())
        <p class="text-gray-600">You have no pending orders yet.</p>
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
        </div>
        @endforeach
    @endif
</div>
@endsection

