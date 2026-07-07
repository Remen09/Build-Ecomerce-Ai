<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order #') . $order->id }}
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm text-gray-500 mb-1">Customer</h3>
                    <p class="font-medium">{{ $order->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm text-gray-500 mb-1">Order Date</h3>
                    <p class="font-medium">{{ $order->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm text-gray-500 mb-1">Status</h3>
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="mt-1">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()"
                            class="text-sm font-semibold rounded-full px-3 py-1 {{ $order->getStatusColor() }} border-0 cursor-pointer">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Shipping Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="font-medium">{{ $order->shipping_address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">City</p>
                        <p class="font-medium">{{ $order->shipping_city }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium">{{ $order->shipping_phone }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            @if ($item->product && $item->product->image)
                                                <img src="{{ Storage::url($item->product->image) }}" alt="" class="w-12 h-12 object-cover rounded mr-3">
                                            @endif
                                            <span class="font-medium">{{ $item->product ? $item->product->name : 'Product Deleted' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-gray-500">${{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-4 text-gray-500">{{ $item->quantity }}</td>
                                    <td class="px-4 py-4 font-medium">${{ number_format($item->quantity * $item->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-medium">Total:</td>
                                <td class="px-4 py-3 font-bold text-lg">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
