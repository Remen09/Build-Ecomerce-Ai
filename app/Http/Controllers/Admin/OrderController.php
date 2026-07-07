<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('user');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('user', 'orderItems.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $order->load('orderItems.product');
        $oldStatus = $order->status;
        $newStatus = $request->validated('status');

        $order->update(['status' => $newStatus]);

        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
