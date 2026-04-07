<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class TestImageCommand extends Command
{
    protected $signature = 'image:test';
    protected $description = 'Test image path generation and accessibility';

    public function handle()
    {
        $this->info('🖼️ Testing Image Paths...');
        
        $product = Product::first();
        
        if (!$product) {
            $this->error('No products found!');
            return;
        }
        
        $this->line("Product: {$product->name}");
        $this->line("Featured Image: " . ($product->featured_image ?? 'NULL'));
        
        if ($product->featured_image) {
            $imagePath = 'storage/' . $product->featured_image;
            $fullPath = public_path($imagePath);
            $url = asset($imagePath);
            
            $this->line("Storage Path: {$imagePath}");
            $this->line("Full Path: {$fullPath}");
            $this->line("URL: {$url}");
            $this->line("File Exists: " . (file_exists($fullPath) ? 'YES' : 'NO'));
            $this->line("File Size: " . (file_exists($fullPath) ? filesize($fullPath) . ' bytes' : 'N/A'));
        }
        
        $this->info('✅ Image test completed!');
    }
}
