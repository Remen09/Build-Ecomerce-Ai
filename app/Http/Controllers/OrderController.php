<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function checkout(): View
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $this->cartService->subtotal();
        $total = $this->cartService->total();

        return view('checkout.index', compact('cart', 'subtotal', 'total'));
    }

    public function store(StoreOrderRequest $request)
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            $total = $this->cartService->total();

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $request->validated('shipping_address'),
                'shipping_city' => $request->validated('shipping_city'),
                'shipping_phone' => $request->validated('shipping_phone'),
            ]);

            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            $this->cartService->clear();

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to place order. Please try again.')
                ->withInput();
        }
    }

    public function show(Order $order): View
    {
        try {
            $this->authorize('view', $order);
        } catch (AuthorizationException $e) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('orders.show', compact('order'));
    }

    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
