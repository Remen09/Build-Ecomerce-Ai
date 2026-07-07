<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Shopping Cart') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (empty($cart))
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-gray-500 text-lg mb-4">Your cart is empty</p>
                    <a href="{{ route('products.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg">
                        Continue Shopping
                    </a>
                </div>
            @else
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach ($cart as $productId => $item)
                            <div class="p-6 flex items-center gap-6" data-product-id="{{ $productId }}">
                                <div class="flex-shrink-0">
                                    @if ($item['image'])
                                        <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded">
                                    @else
                                        <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <a href="{{ route('products.show', $productId) }}" class="hover:text-indigo-600">{{ $item['name'] }}</a>
                                    </h3>
                                    <p class="text-gray-500 mt-1">${{ number_format($item['price'], 2) }} each</p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="flex items-center border rounded">
                                        <button type="button" class="quantity-btn px-3 py-2 text-gray-600 hover:bg-gray-100" data-action="decrease" data-product-id="{{ $productId }}">-</button>
                                        <input type="number" name="quantity" id="qty-{{ $productId }}" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}" class="w-16 text-center border-0 focus:ring-0" readonly>
                                        <button type="button" class="quantity-btn px-3 py-2 text-gray-600 hover:bg-gray-100" data-action="increase" data-product-id="{{ $productId }}">+</button>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900 item-subtotal" id="subtotal-{{ $productId }}">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                </div>

                                <div>
                                    <button type="button" class="text-red-600 hover:text-red-800 remove-btn" data-product-id="{{ $productId }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-gray-50 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Subtotal ({{ $count }} items)</span>
                            <span class="text-2xl font-bold text-gray-900" id="cart-subtotal">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-indigo-600" id="cart-total">${{ number_format($total, 2) }}</span>
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('products.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg text-center">
                                Continue Shopping
                            </a>
                            @auth
                                <a href="{{ route('checkout') }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg text-center">
                                    Proceed to Checkout
                                </a>
                            @else
                                <a href="{{ route('login') }}?redirect=cart" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg text-center">
                                    Login to Checkout
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.quantity-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const action = this.dataset.action;
                    const input = document.getElementById('qty-' + productId);
                    let quantity = parseInt(input.value);
                    const max = parseInt(input.max);

                    if (action === 'increase' && quantity < max) {
                        quantity++;
                    } else if (action === 'decrease' && quantity > 1) {
                        quantity--;
                    }

                    if (quantity !== parseInt(input.value)) {
                        updateQuantity(productId, quantity);
                    }
                });
            });

            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    if (confirm('Remove this item from cart?')) {
                        removeItem(productId);
                    }
                });
            });

            function updateQuantity(productId, quantity) {
                fetch(`/cart/${productId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity: quantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('qty-' + productId).value = quantity;
                        document.getElementById('subtotal-' + productId).textContent = '$' + (quantity * parseFloat(document.querySelector(`[data-product-id="${productId}"]`).dataset.price || 0)).toFixed(2);
                        document.getElementById('cart-subtotal').textContent = '$' + data.subtotal.toFixed(2);
                        document.getElementById('cart-total').textContent = '$' + data.total.toFixed(2);
                        updateCartCount(data.cart_count);
                    } else {
                        alert(data.message);
                    }
                });
            }

            function removeItem(productId) {
                fetch(`/cart/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = document.querySelector(`[data-product-id="${productId}"]`);
                        item.remove();
                        document.getElementById('cart-subtotal').textContent = '$' + data.subtotal.toFixed(2);
                        document.getElementById('cart-total').textContent = '$' + data.total.toFixed(2);
                        updateCartCount(data.cart_count);
                        
                        if (data.cart_count === 0) {
                            location.reload();
                        }
                    }
                });
            }

            function updateCartCount(count) {
                const cartBadge = document.getElementById('cart-count');
                if (cartBadge) {
                    cartBadge.textContent = count;
                    cartBadge.style.display = count > 0 ? 'inline' : 'none';
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
