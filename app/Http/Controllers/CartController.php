<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): View
    {
        $cart = $this->cartService->getCart();
        $subtotal = $this->cartService->subtotal();
        $total = $this->cartService->total();
        $count = $this->cartService->count();

        return view('cart.index', compact('cart', 'subtotal', 'total', 'count'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        $result = $this->cartService->add($product, $quantity);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'cart_count' => $this->cartService->count(),
        ]);
    }

    public function update(Request $request, int $productId): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $result = $this->cartService->update($productId, $request->quantity);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'cart_count' => $this->cartService->count(),
            'subtotal' => $this->cartService->subtotal(),
            'total' => $this->cartService->total(),
        ]);
    }

    public function destroy(int $productId): JsonResponse
    {
        $result = $this->cartService->remove($productId);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'cart_count' => $this->cartService->count(),
            'subtotal' => $this->cartService->subtotal(),
            'total' => $this->cartService->total(),
        ]);
    }

    public function clear(): JsonResponse
    {
        $this->cartService->clear();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared.',
            'cart_count' => 0,
            'subtotal' => 0,
            'total' => 0,
        ]);
    }

    public function count(): JsonResponse
    {
        return response()->json([
            'count' => $this->cartService->count(),
        ]);
    }
}
