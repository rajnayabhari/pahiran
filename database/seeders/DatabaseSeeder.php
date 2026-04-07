<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────
        Admin::create([
            'name' => 'Pahiran Admin',
            'email' => 'admin@pahiran.com',
            'password' => Hash::make('password'),
        ]);

        // ── Seller ─────────────────────────────────────────────
        $seller1 = Seller::create([
            'name' => 'Aarav Shrestha',
            'email' => 'seller@pahiran.com',
            'password' => Hash::make('password'),
            'shop_name' => 'Himalayan Threads',
            'phone' => '9841234567',
            'description' => 'Premium handcrafted Nepali fashion wear',
            'commission_rate' => 10.00,
        ]);

        $seller2 = Seller::create([
            'name' => 'Sita Maharjan',
            'email' => 'sita@pahiran.com',
            'password' => Hash::make('password'),
            'shop_name' => 'Kathmandu Couture',
            'phone' => '9807654321',
            'description' => 'Modern fusion fashion from Kathmandu',
            'commission_rate' => 10.00,
        ]);

        $seller3 = Seller::create([
            'name' => 'Biraj Kalikote',
            'email' => 'biraj@pahiran.com',
            'password' => Hash::make('password'),
            'shop_name' => 'Pokhara Apparel',
            'phone' => '9811223344',
            'description' => 'Adventure and casual wear from the lakeside',
            'commission_rate' => 8.00,
        ]);

        // ── Customer ───────────────────────────────────────────
        $customer = User::create([
            'name' => 'Ram Thapa',
            'email' => 'customer@pahiran.com',
            'password' => Hash::make('password'),
        ]);

        $customer2 = User::create([
            'name' => 'Maya Rana',
            'email' => 'maya@pahiran.com',
            'password' => Hash::make('password'),
        ]);

        // ── Categories ─────────────────────────────────────────
        $menCat = Category::create([
            'name' => 'Men', 
            'slug' => 'men', 
            'image' => 'https://images.unsplash.com/photo-1488161628813-244a2ceba245?q=80&w=1000&auto=format&fit=crop'
        ]);
        $womenCat = Category::create([
            'name' => 'Women', 
            'slug' => 'women', 
            'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=1000&auto=format&fit=crop'
        ]);
        $kidsCat = Category::create([
            'name' => 'Kids', 
            'slug' => 'kids', 
            'image' => 'https://images.unsplash.com/photo-1514090458221-65bb69cf63e6?q=80&w=1000&auto=format&fit=crop'
        ]);
        $accCat = Category::create([
            'name' => 'Accessories', 
            'slug' => 'accessories', 
            'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1000&auto=format&fit=crop'
        ]);

        // Nested
        $menShirts = Category::create(['name' => 'Shirts', 'slug' => 'men-shirts', 'parent_id' => $menCat->id]);
        $menPants = Category::create(['name' => 'Pants', 'slug' => 'men-pants', 'parent_id' => $menCat->id]);
        $womenDresses = Category::create(['name' => 'Dresses', 'slug' => 'women-dresses', 'parent_id' => $womenCat->id]);
        $womenKurtas = Category::create(['name' => 'Kurtas & Sarees', 'slug' => 'women-kurtas', 'parent_id' => $womenCat->id]);

        // ── Products with Variants ─────────────────────────────
        // Seller 1 Products
        $prod1 = Product::create([
            'seller_id' => $seller1->id,
            'category_id' => $menShirts->id,
            'name' => 'Dhaka Topi Print Shirt',
            'slug' => 'dhaka-topi-print-shirt',
            'description' => 'A modern take on traditional Dhaka patterns, crafted in breathable cotton for everyday elegance.',
            'base_price' => 2500.00,
            'featured_image' => 'products/dhaka_shirt.png',
        ]);
        ProductVariant::create(['product_id' => $prod1->id, 'size' => 'M', 'color' => 'Red', 'sku' => 'DTP-M-RED', 'price' => 2500, 'stock' => 15]);
        ProductVariant::create(['product_id' => $prod1->id, 'size' => 'L', 'color' => 'Red', 'sku' => 'DTP-L-RED', 'price' => 2500, 'stock' => 10]);

        $prod2 = Product::create([
            'seller_id' => $seller1->id,
            'category_id' => $menCat->id,
            'name' => 'Pashmina Wool Jacket',
            'slug' => 'pashmina-wool-jacket',
            'description' => 'Luxurious pashmina-blended jacket perfect for Kathmandu winters.',
            'base_price' => 8500.00,
            'featured_image' => 'products/pashmina_jacket.png',
        ]);
        ProductVariant::create(['product_id' => $prod2->id, 'size' => 'M', 'color' => 'Charcoal', 'sku' => 'PWJ-M-CHR', 'price' => 8500, 'stock' => 5]);

        // Seller 2 Products
        $prod3 = Product::create([
            'seller_id' => $seller2->id,
            'category_id' => $womenDresses->id,
            'name' => 'Nepali Silk Fusion Dress',
            'slug' => 'nepali-silk-fusion-dress',
            'description' => 'Elegant silk dress blending traditional Nepali patterns with contemporary design.',
            'base_price' => 6500.00,
            'featured_image' => 'products/silk_dress.png',
        ]);
        ProductVariant::create(['product_id' => $prod3->id, 'size' => 'S', 'color' => 'Gold', 'sku' => 'NSF-S-GLD', 'price' => 6500, 'stock' => 7]);

        $prod4 = Product::create([
            'seller_id' => $seller2->id,
            'category_id' => $womenKurtas->id,
            'name' => 'Hand-Embroidered Chiffon Saree',
            'slug' => 'hand-embroidered-chiffon-saree',
            'description' => 'Beautifully hand-embroidered chiffon saree for special occasions.',
            'base_price' => 12500.00,
            'featured_image' => 'products/chiffon_saree.png',
        ]);
        ProductVariant::create(['product_id' => $prod4->id, 'size' => 'Free Size', 'color' => 'Royal Blue', 'sku' => 'HEC-FS-BLU', 'price' => 12500, 'stock' => 5]);

        // Seller 3 Products
        $prod5 = Product::create([
            'seller_id' => $seller3->id,
            'category_id' => $menPants->id,
            'name' => 'Himalayan Trekking Trousers',
            'slug' => 'himalayan-trekking-trousers',
            'description' => 'Durable and water-resistant trousers designed for the rugged trails of Nepal.',
            'base_price' => 4200.00,
            'featured_image' => 'products/trekking_trousers.png',
        ]);
        ProductVariant::create(['product_id' => $prod5->id, 'size' => '32', 'color' => 'Olive', 'sku' => 'HTT-32-OLV', 'price' => 4200, 'stock' => 12]);
        ProductVariant::create(['product_id' => $prod5->id, 'size' => '34', 'color' => 'Olive', 'sku' => 'HTT-34-OLV', 'price' => 4200, 'stock' => 15]);

        $prod6 = Product::create([
            'seller_id' => $seller3->id,
            'category_id' => $kidsCat->id,
            'name' => 'Mini Sherpa Hooded Fleece',
            'slug' => 'mini-sherpa-hooded-fleece',
            'description' => 'Warm and cozy sherpa fleece for the little adventurers.',
            'base_price' => 2800.00,
            'featured_image' => 'products/kids_fleece.png',
        ]);
        ProductVariant::create(['product_id' => $prod6->id, 'size' => '4-6Y', 'color' => 'Red', 'sku' => 'MSH-46-RED', 'price' => 2800, 'stock' => 20]);

        $prod7 = Product::create([
            'seller_id' => $seller1->id,
            'category_id' => $accCat->id,
            'name' => 'Yak Leather Wallet',
            'slug' => 'yak-leather-wallet',
            'description' => 'Minimalist genuine yak leather wallet.',
            'base_price' => 1500.00,
            'featured_image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?q=80&w=1000&auto=format&fit=crop',
        ]);
        ProductVariant::create(['product_id' => $prod7->id, 'size' => 'Standard', 'color' => 'Tan', 'sku' => 'YLW-STD-TAN', 'price' => 1500, 'stock' => 25]);

        // ── Sample Orders ───────────────────────────────────────
        // Order 1: Completed Khalti
        $order1 = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'PAH-DEMO-001',
            'total_amount' => 9000.00,
            'commission_amount' => 900.00,
            'status' => 'delivered',
            'shipping_address' => 'Thamel, Kathmandu',
            'phone' => '9841000001',
        ]);
        $order1->items()->create([
            'product_id' => $prod1->id,
            'product_variant_id' => $prod1->variants->first()->id,
            'seller_id' => $seller1->id,
            'quantity' => 1,
            'unit_price' => 2500.00,
            'commission' => 250.00,
        ]);
        $order1->items()->create([
            'product_id' => $prod3->id,
            'product_variant_id' => $prod3->variants->first()->id,
            'seller_id' => $seller2->id,
            'quantity' => 1,
            'unit_price' => 6500.00,
            'commission' => 650.00,
        ]);
        Payment::create([
            'order_id' => $order1->id,
            'transaction_id' => 'KHALTI-DEMO-1',
            'payment_method' => 'khalti',
            'amount' => 9000.00,
            'status' => 'completed',
            'paid_at' => now()->subDays(2),
        ]);

        // Order 2: Pending COD
        $order2 = Order::create([
            'user_id' => $customer2->id,
            'order_number' => 'PAH-DEMO-002',
            'total_amount' => 4200.00,
            'commission_amount' => 336.00,
            'status' => 'pending',
            'shipping_address' => 'Lakeside, Pokhara',
            'phone' => '9801000002',
            'notes' => 'Please call before delivery.',
        ]);
        $order2->items()->create([
            'product_id' => $prod5->id,
            'product_variant_id' => $prod5->variants->first()->id,
            'seller_id' => $seller3->id,
            'quantity' => 1,
            'unit_price' => 4200.00,
            'commission' => 336.00,
        ]);
        Payment::create([
            'order_id' => $order2->id,
            'payment_method' => 'cod',
            'amount' => 4200.00,
            'status' => 'pending',
        ]);

        // Order 3: Processing Khalti
        $order3 = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'PAH-DEMO-003',
            'total_amount' => 12500.00,
            'commission_amount' => 1250.00,
            'status' => 'processing',
            'shipping_address' => 'Jhamsikhel, Lalitpur',
            'phone' => '9841000001',
        ]);
        $order3->items()->create([
            'product_id' => $prod4->id,
            'product_variant_id' => $prod4->variants->first()->id,
            'seller_id' => $seller2->id,
            'quantity' => 1,
            'unit_price' => 12500.00,
            'commission' => 1250.00,
        ]);
        Payment::create([
            'order_id' => $order3->id,
            'transaction_id' => 'KHALTI-DEMO-2',
            'payment_method' => 'khalti',
            'amount' => 12500.00,
            'status' => 'completed',
            'paid_at' => now()->subDay(),
        ]);
    }
}
