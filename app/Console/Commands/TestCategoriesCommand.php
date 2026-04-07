<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;

class TestCategoriesCommand extends Command
{
    protected $signature = 'categories:test';
    protected $description = 'Test and display all categories';

    public function handle()
    {
        $this->info('🏷️ Testing Clothing Categories...');
        
        $categories = Category::all(['id', 'name', 'slug', 'is_active']);
        
        $this->info("Total Categories: {$categories->count()}");
        $this->line("");
        
        foreach ($categories as $category) {
            $status = $category->is_active ? '✅ Active' : '❌ Inactive';
            $this->line("{$category->id}: {$category->name} ({$category->slug}) - {$status}");
        }
        
        $this->line("");
        $this->info('✅ Categories test completed!');
    }
}
