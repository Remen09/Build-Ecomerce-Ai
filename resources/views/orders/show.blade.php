<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order #') . $order->id }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Order Date</p>
                        <p class="font-medium">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->getStatusColor() }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="font-medium text-lg">${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Items</p>
                        <p class="font-medium">{{ $order->orderItems->count() }} items</p>
                    </div>
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
                <div class="divide-y divide-gray-200">
                    @foreach ($order->orderItems as $item)
                        <div class="py-4 flex items-center gap-4">
                            <div class="flex-shrink-0">
                                @if ($item->product && $item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $item->product ? $item->product->name : 'Product Deleted' }}</p>
                                <p class="text-sm text-gray-500">{{ $item->quantity }} x ${{ number_format($item->price, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">${{ number_format($item->quantity * $item->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="border-t mt-4 pt-4">
                    <div class="flex justify-between text-xl font-bold">
                        <span>Total</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
