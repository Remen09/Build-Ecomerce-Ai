<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Checkout') }}
            </h2>
            <a href="{{ route('cart.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Cart
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4">Shipping Information</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Shipping Address</label>
                                    <textarea name="shipping_address" id="shipping_address" rows="3" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('shipping_address') border-red-500 @enderror">{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city') }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('shipping_city') border-red-500 @enderror">
                                    @error('shipping_city')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="text" name="shipping_phone" id="shipping_phone" value="{{ old('shipping_phone') }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('shipping_phone') border-red-500 @enderror">
                                    @error('shipping_phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6 mt-6">
                            <h3 class="text-lg font-semibold mb-4">Payment Method</h3>
                            <p class="text-gray-600">Cash on Delivery (COD)</p>
                            <p class="text-sm text-gray-500 mt-1">You will pay when you receive your order.</p>
                        </div>
                    </div>

                    <div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                            
                            <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                                @foreach ($cart as $productId => $item)
                                    <div class="py-3 flex items-center gap-4">
                                        <div class="flex-shrink-0">
                                            @if ($item['image'])
                                                <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $item['name'] }}</p>
                                            <p class="text-sm text-gray-500">Qty: {{ $item['quantity'] }}</p>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">
                                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t mt-4 pt-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                                <div class="flex justify-between text-xl font-bold text-gray-900 mt-4 pt-4 border-t">
                                    <span>Total</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
