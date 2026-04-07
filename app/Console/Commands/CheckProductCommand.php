<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CheckProductCommand extends Command
{
    protected $signature = 'product:check';
    protected $description = 'Check product data and image paths';

    public function handle()
    {
        $this->info('🔍 Checking Product Data...');
        
        $products = Product::with('seller', 'category')->get();
        
        $this->info("Total Products: {$products->count()}");
        $this->line("");
        
        foreach ($products as $product) {
            $this->line("ID: {$product->id}");
            $this->line("Name: {$product->name}");
            $this->line("Seller: {$product->seller->shop_name}");
            $this->line("Category: {$product->category->name}");
            $this->line("Featured Image: " . ($product->featured_image ?? 'NULL'));
            $this->line("Image Path: " . ($product->featured_image ? asset('storage/' . $product->featured_image) : 'NULL'));
            $this->line("Active: " . ($product->is_active ? 'Yes' : 'No'));
            $this->line("Base Price: Rs. {$product->base_price}");
            $this->line("Variants: {$product->variants->count()}");
            $this->line(str_repeat('-', 50));
        }
        
        $this->info('✅ Product check completed!');
    }
}
