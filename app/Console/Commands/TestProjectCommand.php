<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Seller;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Category;

class TestProjectCommand extends Command
{
    protected $signature = 'project:test';
    protected $description = 'Test all project components are working';

    public function handle()
    {
        $this->info('🔍 Testing Pahiran Website Project...');
        
        // Test Controllers
        $this->testControllers();
        
        // Test Models
        $this->testModels();
        
        // Test Routes
        $this->testRoutes();
        
        // Test Database Connection
        $this->testDatabase();
        
        // Test Views
        $this->testViews();
        
        $this->info('✅ Project test completed successfully!');
    }
    
    private function testControllers()
    {
        $this->info('📁 Testing Controllers...');
        
        $controllers = [
            'App\Http\Controllers\StorefrontController',
            'App\Http\Controllers\CartController',
            'App\Http\Controllers\CheckoutController',
            'App\Http\Controllers\WishlistController',
            'App\Http\Controllers\Auth\AuthController',
            'App\Http\Controllers\Admin\AuthController',
            'App\Http\Controllers\Admin\DashboardController',
            'App\Http\Controllers\Admin\ProductController',
            'App\Http\Controllers\Admin\OrderController',
            'App\Http\Controllers\Admin\SellerController',
            'App\Http\Controllers\Admin\UserController',
            'App\Http\Controllers\Seller\AuthController',
            'App\Http\Controllers\Seller\DashboardController',
            'App\Http\Controllers\Seller\ProductController',
            'App\Http\Controllers\Seller\OrderController',
        ];
        
        foreach ($controllers as $controller) {
            if (class_exists($controller)) {
                $this->info("  ✅ {$controller}");
            } else {
                $this->error("  ❌ {$controller} - NOT FOUND");
            }
        }
    }
    
    private function testModels()
    {
        $this->info('📊 Testing Models...');
        
        $models = [
            'App\Models\User',
            'App\Models\Seller',
            'App\Models\Admin',
            'App\Models\Product',
            'App\Models\Category',
            'App\Models\Order',
            'App\Models\OrderItem',
            'App\Models\Payment',
            'App\Models\ProductVariant',
            'App\Models\Wishlist',
        ];
        
        foreach ($models as $model) {
            if (class_exists($model)) {
                $this->info("  ✅ {$model}");
            } else {
                $this->error("  ❌ {$model} - NOT FOUND");
            }
        }
    }
    
    private function testRoutes()
    {
        $this->info('🛣️ Testing Routes...');
        
        $routes = [
            'home',
            'login',
            'register',
            'cart.index',
            'checkout.index',
            'seller.login',
            'seller.dashboard',
            'admin.login',
            'admin.dashboard',
        ];
        
        foreach ($routes as $routeName) {
            try {
                $route = Route::getRoutes()->getByName($routeName);
                if ($route) {
                    $this->info("  ✅ Route '{$routeName}' exists");
                } else {
                    $this->error("  ❌ Route '{$routeName}' - NOT FOUND");
                }
            } catch (\Exception $e) {
                $this->error("  ❌ Route '{$routeName}' - ERROR: {$e->getMessage()}");
            }
        }
    }
    
    private function testDatabase()
    {
        $this->info('🗄️ Testing Database...');
        
        try {
            // Test database connection
            \DB::connection()->getPdo();
            $this->info("  ✅ Database connection successful");
            
            // Test tables exist
            $tables = ['users', 'sellers', 'admins', 'products', 'categories', 'orders', 'order_items', 'payments', 'wishlists'];
            
            foreach ($tables as $table) {
                if (\Schema::hasTable($table)) {
                    $this->info("  ✅ Table '{$table}' exists");
                } else {
                    $this->error("  ❌ Table '{$table}' - NOT FOUND");
                }
            }
            
        } catch (\Exception $e) {
            $this->error("  ❌ Database connection failed: {$e->getMessage()}");
        }
    }
    
    private function testViews()
    {
        $this->info('🎨 Testing Views...');
        
        $views = [
            'welcome',
            'layouts.app',
            'auth.login',
            'auth.register',
            'storefront.index',
            'storefront.product',
            'seller.dashboard',
            'admin.dashboard',
        ];
        
        foreach ($views as $view) {
            try {
                if (view()->exists($view)) {
                    $this->info("  ✅ View '{$view}' exists");
                } else {
                    $this->warn("  ⚠️  View '{$view}' - NOT FOUND (may be optional)");
                }
            } catch (\Exception $e) {
                $this->error("  ❌ View '{$view}' - ERROR: {$e->getMessage()}");
            }
        }
    }
}
