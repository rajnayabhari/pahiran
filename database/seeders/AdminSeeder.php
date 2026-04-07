<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Pahiran Admin',
            'email' => 'admin@pahiran.com',
            'password' => Hash::make('password'),
        ]);

        $this->command->info('Admin user created successfully!');
    }
}
