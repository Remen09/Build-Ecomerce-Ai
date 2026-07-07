<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        $clothing = Category::create(['name' => 'Clothing', 'slug' => 'clothing']);
        $home = Category::create(['name' => 'Home & Garden', 'slug' => 'home-garden']);

        Product::create(['name' => 'Wireless Headphones', 'category_id' => $electronics->id, 'price' => 79.99, 'stock' => 50, 'description' => 'High-quality wireless headphones with noise cancellation.', 'status' => 'active']);
        Product::create(['name' => 'Smart Watch', 'category_id' => $electronics->id, 'price' => 199.99, 'stock' => 30, 'description' => 'Feature-rich smart watch with health monitoring.', 'status' => 'active']);
        Product::create(['name' => 'Bluetooth Speaker', 'category_id' => $electronics->id, 'price' => 49.99, 'stock' => 100, 'description' => 'Portable bluetooth speaker.', 'status' => 'active']);
        Product::create(['name' => 'Cotton T-Shirt', 'category_id' => $clothing->id, 'price' => 24.99, 'stock' => 150, 'description' => 'Comfortable 100% cotton t-shirt.', 'status' => 'active']);
        Product::create(['name' => 'Denim Jeans', 'category_id' => $clothing->id, 'price' => 59.99, 'stock' => 75, 'description' => 'Classic fit denim jeans.', 'status' => 'active']);
        Product::create(['name' => 'Plant Pot', 'category_id' => $home->id, 'price' => 19.99, 'stock' => 60, 'description' => 'Decorative ceramic plant pot.', 'status' => 'active']);
        Product::create(['name' => 'Garden Tools Set', 'category_id' => $home->id, 'price' => 34.99, 'stock' => 25, 'description' => 'Complete garden tools set.', 'status' => 'active']);
    }
}
