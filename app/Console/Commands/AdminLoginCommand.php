<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;

class AdminLoginCommand extends Command
{
    protected $signature = 'admin:login';
    protected $description = 'Show admin login credentials';

    public function handle()
    {
        $this->info('🔑 Admin Login Credentials');
        
        $admins = Admin::get(['id', 'name', 'email', 'created_at']);
        
        if ($admins->isEmpty()) {
            $this->error('No admin users found in database!');
            return;
        }
        
        foreach ($admins as $admin) {
            $this->line("ID: {$admin->id}");
            $this->line("Name: {$admin->name}");
            $this->line("Email: {$admin->email}");
            $this->line("Password: password");
            $this->line("Created: {$admin->created_at}");
            $this->line(str_repeat('-', 50));
        }
        
        $this->info('✅ Admin credentials displayed above!');
    }
}
