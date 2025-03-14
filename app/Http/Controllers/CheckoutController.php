<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $cartItems = collect($request->input('items', [])); // Get items from JSON request

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 400);
        }

        // Simulating PayMongo API success (replace with real API response)
        $paymentSuccess = true;

        if ($paymentSuccess) {
            DB::transaction(function () use ($cartItems, $user) {
                $total = $cartItems->sum(fn($item) => $item['price'] * $item['quantity']);
                $order = Order::create([
                    'user_id' => $user->id,
                    'total'   => $total,
                    'status'  => 'PENDING',
                ]);

                foreach ($cartItems as $item) {
                    // Use product_id if provided; otherwise, fallback to id.
                    $productId = isset($item['product_id']) ? $item['product_id'] : $item['id'];

                    // Get the product and check if it exists
                    $product = Product::find($productId);
                    if (!$product) {
                        Log::error("Product with ID {$productId} not found. Skipping OrderItem creation.");
                        continue;
                    }
                    
                    // Check for sufficient stock before proceeding
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$product->name}.");
                    }
                    
                    // Update the stock dynamically
                    $product->decrement('stock', $item['quantity']);
                    
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                    ]);
                }
                // Remove cart items for the user
                CartItem::where('user_id', $user->id)->delete();
            });

            return response()->json(['success' => true, 'message' => 'Order placed successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Payment failed. Try again.'], 400);
        }
    }
}
