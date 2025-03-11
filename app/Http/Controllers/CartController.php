<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Fetch all cart items for the logged-in user
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        return response()->json($cartItems);
    }

    // Add item to cart
    public function store(Request $request)
    {
        Log::info('CartController@store called for product: ' . $request->product_id);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $cartItem = CartItem::where([
            'user_id'    => $user->id,
            'product_id' => $request->product_id
        ])->first();

        if ($cartItem) {
            // Increment the existing quantity by the requested amount
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cartItem = CartItem::create([
                'user_id'    => $user->id,
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity
            ]);
        }

        return response()->json([
            'success'  => true,
            'cartItem' => $cartItem
        ]);
    }

    // Update cart quantity
    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cartItem->update(['quantity' => $request->quantity]);
        return response()->json($cartItem);
    }

    // Remove item from cart
    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cartItem->delete();
        return response()->json(['message' => 'Item removed']);
    }
}
