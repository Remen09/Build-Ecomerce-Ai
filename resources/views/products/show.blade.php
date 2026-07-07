<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">
                    <div class="relative">
                        @if ($product->image)
                            <img src="{{ Storage::url($product->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full rounded-lg shadow">
                        @else
                            <div class="w-full aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        @if ($product->stock <= 5 && $product->stock > 0)
                            <span class="absolute top-4 left-4 bg-orange-500 text-white text-sm px-3 py-1 rounded-full">Low Stock: Only {{ $product->stock }} left</span>
                        @endif
                    </div>

                    <div class="flex flex-col">
                        <span class="text-indigo-600 font-medium mb-2">{{ $product->category->name }}</span>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                        
                        <div class="flex items-center mb-6">
                            <span class="text-4xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            @if ($product->inStock())
                                <span class="ml-4 bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">In Stock</span>
                            @else
                                <span class="ml-4 bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full">Out of Stock</span>
                            @endif
                        </div>

                        <div class="prose prose-sm max-w-none text-gray-600 mb-6">
                            <p>{{ $product->description ?: 'No description available.' }}</p>
                        </div>

                        <div class="border-t pt-6 mt-auto">
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-gray-600">
                                    <span class="font-medium">SKU:</span> {{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-gray-600">
                                    <span class="font-medium">Available:</span> {{ $product->stock }} units
                                </div>
                            </div>

                            @if ($product->inStock())
                                <form id="add-to-cart-form" class="flex gap-4">
                                    @csrf
                                    <div class="flex items-center border rounded">
                                        <button type="button" class="px-4 py-2 text-gray-600 hover:bg-gray-100" id="decrement-btn">-</button>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center border-0 focus:ring-0" readonly>
                                        <button type="button" class="px-4 py-2 text-gray-600 hover:bg-gray-100" id="increment-btn">+</button>
                                    </div>
                                    <button type="submit" id="add-to-cart-btn" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                        Add to Cart
                                    </button>
                                </form>
                                <div id="cart-message" class="mt-3 hidden"></div>
                            @else
                                <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3 px-6 rounded-lg cursor-not-allowed">
                                    Out of Stock
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const incrementBtn = document.getElementById('increment-btn');
            const decrementBtn = document.getElementById('decrement-btn');
            const quantityInput = document.getElementById('quantity');
            const addToCartForm = document.getElementById('add-to-cart-form');
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            const cartMessage = document.getElementById('cart-message');
            const productId = {{ $product->id }};

            if (incrementBtn) {
                incrementBtn.addEventListener('click', function() {
                    const max = parseInt(quantityInput.max);
                    if (parseInt(quantityInput.value) < max) {
                        quantityInput.value = parseInt(quantityInput.value) + 1;
                    }
                });
            }

            if (decrementBtn) {
                decrementBtn.addEventListener('click', function() {
                    if (parseInt(quantityInput.value) > 1) {
                        quantityInput.value = parseInt(quantityInput.value) - 1;
                    }
                });
            }

            if (addToCartForm) {
                addToCartForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const quantity = parseInt(quantityInput.value);
                    const originalText = addToCartBtn.textContent;
                    addToCartBtn.textContent = 'Adding...';
                    addToCartBtn.disabled = true;

                    fetch('{{ route('cart.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        addToCartBtn.textContent = originalText;
                        addToCartBtn.disabled = false;

                        cartMessage.classList.remove('hidden');
                        if (data.success) {
                            cartMessage.innerHTML = '<span class="text-green-600">' + data.message + ' <a href="{{ route('cart.index') }}" class="underline">View Cart</a></span>';
                            updateCartCount(data.cart_count);
                        } else {
                            cartMessage.innerHTML = '<span class="text-red-600">' + data.message + '</span>';
                        }

                        setTimeout(() => {
                            cartMessage.classList.add('hidden');
                        }, 5000);
                    })
                    .catch(error => {
                        addToCartBtn.textContent = originalText;
                        addToCartBtn.disabled = false;
                        cartMessage.classList.remove('hidden');
                        cartMessage.innerHTML = '<span class="text-red-600">Something went wrong. Please try again.</span>';
                    });
                });
            }

            function updateCartCount(count) {
                const cartCount = document.getElementById('cart-count');
                const cartCountMobile = document.getElementById('cart-count-mobile');
                if (cartCount) {
                    cartCount.textContent = count;
                    cartCount.style.display = count > 0 ? 'inline' : 'none';
                }
                if (cartCountMobile) {
                    cartCountMobile.textContent = count;
                    cartCountMobile.style.display = count > 0 ? 'flex' : 'none';
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
