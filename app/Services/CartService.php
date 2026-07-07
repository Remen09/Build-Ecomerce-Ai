<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected function fetchCart(): array
    {
        return Session::get('cart', []);
    }

    protected function save(array $cart): void
    {
        Session::put('cart', $cart);
    }

    public function add(Product $product, int $quantity = 1): array
    {
        $cart = $this->fetchCart();
        $productId = $product->id;

        if (! isset($cart[$productId])) {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => 0,
                'image' => $product->image,
                'stock' => $product->stock,
            ];
        }

        $newQuantity = $cart[$productId]['quantity'] + $quantity;

        if ($newQuantity > $product->stock) {
            return [
                'success' => false,
                'message' => 'Not enough stock available.',
            ];
        }

        $cart[$productId]['quantity'] = $newQuantity;
        $this->save($cart);

        return [
            'success' => true,
            'message' => 'Product added to cart.',
        ];
    }

    public function update(int $productId, int $quantity): array
    {
        $cart = $this->fetchCart();

        if (! isset($cart[$productId])) {
            return [
                'success' => false,
                'message' => 'Product not found in cart.',
            ];
        }

        if ($quantity <= 0) {
            return $this->remove($productId);
        }

        if ($quantity > $cart[$productId]['stock']) {
            return [
                'success' => false,
                'message' => 'Not enough stock available.',
            ];
        }

        $cart[$productId]['quantity'] = $quantity;
        $this->save($cart);

        return [
            'success' => true,
            'message' => 'Cart updated.',
        ];
    }

    public function remove(int $productId): array
    {
        $cart = $this->fetchCart();

        if (! isset($cart[$productId])) {
            return [
                'success' => false,
                'message' => 'Product not found in cart.',
            ];
        }

        unset($cart[$productId]);
        $this->save($cart);

        return [
            'success' => true,
            'message' => 'Product removed from cart.',
        ];
    }

    public function clear(): void
    {
        $this->save([]);
    }

    public function getCart(): array
    {
        return $this->fetchCart();
    }

    public function getCartItems(): array
    {
        return $this->fetchCart();
    }

    public function count(): int
    {
        $cart = $this->fetchCart();

        return array_sum(array_column($cart, 'quantity'));
    }

    public function subtotal(): float
    {
        $cart = $this->fetchCart();
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return round($subtotal, 2);
    }

    public function total(): float
    {
        return $this->subtotal();
    }

    public function isEmpty(): bool
    {
        return empty($this->fetchCart());
    }
}
