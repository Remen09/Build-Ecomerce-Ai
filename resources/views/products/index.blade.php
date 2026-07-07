<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Our Products') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6">
                <aside class="md:w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="font-semibold text-lg mb-4">Categories</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('products.index') }}" 
                                   class="block px-3 py-2 rounded {{ !request('category') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                    All Products
                                </a>
                            </li>
                            @foreach ($categories as $category)
                                <li>
                                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                                       class="block px-3 py-2 rounded {{ request('category') == $category->id ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>

                <div class="flex-1">
                    <div class="bg-white rounded-lg shadow mb-6 p-4">
                        <form action="{{ route('products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                            @if (request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <div class="flex-1">
                                <input type="text" name="search" placeholder="Search products..." 
                                       value="{{ request('search') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <select name="sort" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                                Filter
                            </button>
                        </form>
                    </div>

                    @if ($products->isEmpty())
                        <div class="bg-white rounded-lg shadow p-12 text-center">
                            <p class="text-gray-500 text-lg">No products found.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($products as $product)
                                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                                    <a href="{{ route('products.show', $product->slug ?: $product->id) }}" class="group">
                                        <div class="aspect-square bg-gray-100 relative">
                                            @if ($product->image)
                                                <img src="{{ Storage::url($product->image) }}" 
                                                     alt="{{ $product->name }}"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            @if ($product->stock <= 5 && $product->stock > 0)
                                                <span class="absolute top-2 left-2 bg-orange-500 text-white text-xs px-2 py-1 rounded">Low Stock</span>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <p class="text-sm text-indigo-600 mb-1">{{ $product->category->name }}</p>
                                            <h3 class="font-semibold text-gray-800 mb-2 group-hover:text-indigo-600 transition-colors">
                                                {{ $product->name }}
                                            </h3>
                                            <p class="text-gray-500 text-sm mb-2 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                                <span class="text-sm text-gray-500">{{ $product->stock }} in stock</span>
                                            </div>
                                        </div>
                                    </a>
                                    @if($product->inStock())
                                    <div class="px-4 pb-4">
                                        <?php $pid = $product->id; ?>
                                        <button type="button" class="mt-3 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="addToCart(<?php echo $pid; ?>)">
                                            Add to Cart
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addToCart(productId) {
            console.log('Adding product:', productId);
            
            var btn = event.target;
            var originalText = btn.innerText;
            btn.innerText = 'Adding...';
            btn.disabled = true;

            fetch('{{ route("cart.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                btn.innerText = originalText;
                btn.disabled = false;
                
                if (data.success) {
                    alert(data.message);
                    updateCartCount(data.cart_count);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerText = originalText;
                btn.disabled = false;
                alert('Something went wrong. Please try again.');
            });
        }

        function updateCartCount(count) {
            var cartCount = document.getElementById('cart-count');
            var cartCountMobile = document.getElementById('cart-count-mobile');
            if (cartCount) {
                cartCount.textContent = count;
                cartCount.style.display = count > 0 ? 'inline' : 'none';
            }
            if (cartCountMobile) {
                cartCountMobile.textContent = count;
                cartCountMobile.style.display = count > 0 ? 'flex' : 'none';
            }
        }
    </script>
    @endpush
</x-app-layout>
