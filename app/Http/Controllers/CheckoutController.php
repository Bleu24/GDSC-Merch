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
use GuzzleHttp\Client;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $cartItems = collect($request->input('items', []));
    
        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 400);
        }
    
        // Calculate total amount (assuming price is in PHP; convert to centavos)
        $total = $cartItems->sum(fn($item) => $item['price'] * $item['quantity']);
        $amountInCentavos = $total * 100;
    
        // Create a payment link via PayMongo API
        $client = new Client();
        $paymongoSecret = env('PAYMONGO_SECRET_KEY');
        try {
            $response = $client->post('https://api.paymongo.com/v1/links', [
                'body' => json_encode([
                    'data' => [
                        'attributes' => [
                            'amount' => $amountInCentavos,
                            'description' => 'Payment for order',
                            'remarks' => 'Choose a payment method'
                        ]
                    ]
                ]),
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    // Basic auth using base64 encode of secret with a colon (no password)
                    'authorization' => 'Basic ' . base64_encode($paymongoSecret . ':'),
                ],
            ]);
    
            $paymongoData = json_decode($response->getBody(), true);
            // Retrieve the payment link URL from the response
            $paymentLink = $paymongoData['data']['attributes']['checkout_url'] ?? null;
    
            if (!$paymentLink) {
                throw new \Exception("Payment link not generated.");
            }
        } catch (\Exception $e) {
            Log::error("PayMongo API error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Payment could not be initiated.'], 500);
        }
    
        // At this point, you can send the payment link to the frontend so the customer can complete payment.
        // For demonstration purposes, we'll proceed to create the order if the payment link is generated.
        DB::transaction(function () use ($cartItems, $user, $total) {
            $order = Order::create([
                'user_id' => $user->id,
                'total'   => $total,
                'status'  => 'PENDING',
            ]);
    
            foreach ($cartItems as $item) {
                // Use product_id if available; otherwise, fallback to id.
                $productId = isset($item['product_id']) ? $item['product_id'] : $item['id'];
    
                $product = Product::find($productId);
                if (!$product) {
                    Log::error("Product with ID {$productId} not found. Skipping OrderItem creation.");
                    continue;
                }
    
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}.");
                }
    
                // Reduce product stock
                $product->decrement('stock', $item['quantity']);
    
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }
    
            CartItem::where('user_id', $user->id)->delete();
        });
    
        // Return the payment link (client must complete the payment at the link)
        return response()->json([
            'success' => true, 
            'message' => 'Order placed successfully! Please complete your payment.',
            'payment_link' => $paymentLink
        ]);
    }
}
